<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Package;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\Warehouse;

class Dashboard extends Component
{
    public $days = 30;
    public $warehouse_id = '';

    public function mount()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // If Customer, always go to portal
        if ($user->role === 'customer') {
            return redirect()->route('customer.dashboard');
        }

        // If SuperAdmin and NO tenant context, go to super dashboard
        if ($user->role === 'superadmin' && !session()->has('tenant_id')) {
            return redirect()->route('super.dashboard');
        }
    }

    public function setFilter($days)
    {
        $this->days = $days;
    }

    public function refresh()
    {
        // Just trigger re-render
    }

    public function render()
    {
        try {
            $dateLimit = now()->subDays($this->days);

            $packagesQuery = Package::where('created_at', '>=', $dateLimit);
            if ($this->warehouse_id) {
                $packagesQuery->where('warehouse_id', $this->warehouse_id);
            }

            $recent_packages = Package::with(['customer.user'])->latest()->take(6)->get();

            $monthFormat = \App\Helpers\DatabaseHelper::formatMonth('created_at', '%m');

            // --- FINANCIAL METRICS (Smart Reception) ---
            $financialMetrics = Package::selectRaw('
                SUM(CASE WHEN client_total_billed IS NOT NULL AND provider_cost IS NOT NULL THEN (client_total_billed - provider_cost) ELSE 0 END) as total_profit,
                COUNT(CASE WHEN client_total_billed IS NOT NULL AND provider_cost IS NOT NULL AND provider_cost > client_total_billed THEN 1 END) as leaks_count,
                AVG(CASE WHEN provider_cost > 0 AND client_total_billed IS NOT NULL THEN ((client_total_billed - provider_cost) / provider_cost * 100) ELSE NULL END) as avg_roi
            ')->first();

            // Projected Profit from stock (Received but not billed)
            $tenantSettings = \App\Models\Tenant::find(session('tenant_id'))->settings_json ?? [];
            $defaultRate = $tenantSettings['default_rate'] ?? 2.50;

            $projectedRevenue = Package::whereNotIn('status', ['delivered', 'cancelled'])
                ->whereNull('client_total_billed')
                ->where('weight', '>', 0)
                ->sum('weight') * $defaultRate;

            $projectedCost = Package::whereNotIn('status', ['delivered', 'cancelled'])
                ->whereNull('provider_cost')
                ->where('weight', '>', 0)
                ->sum('weight') * 1.00; // Estimated $1 per lb cost

            $projectedProfit = $projectedRevenue - $projectedCost;

            // Prepare Chart Data
            $monthlyMovement = Package::selectRaw("$monthFormat as month, count(*) as count")
                ->where('created_at', '>=', now()->startOfYear())
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();

            $chartData = array_fill(1, 12, 0);
            foreach ($monthlyMovement as $month => $count) {
                $chartData[(int)$month] = $count;
            }

            // Prepare Revenue Chart Data
            $monthlyRevenue = Invoice::selectRaw("$monthFormat as month, sum(total) as total")
                ->where('created_at', '>=', now()->startOfYear())
                ->where('status', '!=', 'cancelled')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
                ->toArray();

            // Real Costs Data (from provider_cost in packages)
            $monthlyCosts = Package::selectRaw("$monthFormat as month, sum(provider_cost) as total_cost")
                ->where('created_at', '>=', now()->startOfYear())
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total_cost', 'month')
                ->toArray();

            $revenueData = array_fill(1, 12, 0);
            $costData = array_fill(1, 12, 0);
            $projectionData = array_fill(1, 12, 0);

            foreach ($monthlyRevenue as $month => $total) {
                $revenueData[(int)$month] = $total;
            }

            foreach ($monthlyCosts as $month => $cost) {
                $costData[(int)$month] = $cost;
            }

            // Calculation weights for projection... (keeping existing projection logic)
            $sumRevenue = array_sum($revenueData);
            $totalMonthsWithData = count(array_filter($revenueData));

            // Calculation weights
            $avgRevenue = $totalMonthsWithData > 0 ? ($sumRevenue / $totalMonthsWithData) : 100;
            $newCustomersWeight = Customer::where('created_at', '>=', now()->subDays(30))->count();
            $packageVolume = Package::where('created_at', '>=', now()->subDays(30))->count();

            // Heuristic: Each customer adds 5% potential, each 10 packages add 1% potential to the baseline
            $momentumFactor = 1 + (($newCustomersWeight * 0.05) + ($packageVolume * 0.001));
            $currentMonth = (int)date('m');

            for ($i = 1; $i <= 12; $i++) {
                if ($i < $currentMonth) {
                    // Historical Projection (Trendline of what was expected)
                    $projectionData[$i] = round($avgRevenue, 2);
                } else {
                    // Future Projection (Compounded momentum based on new acquisitions and volume)
                    $projectionData[$i] = round($avgRevenue * pow($momentumFactor, ($i - $currentMonth + 1)), 2);
                }
            }

            $warehouseUsage = Package::whereNotIn('status', ['delivered', 'cancelled'])
                ->join('warehouses', 'packages.warehouse_id', '=', 'warehouses.id')
                ->selectRaw('warehouses.name, count(*) as count')
                ->groupBy('warehouses.name')
                ->pluck('count', 'name')
                ->toArray();

            // ACTIONABLE ALERTS LOGIC
            $actionAlerts = [];

            // 1. Overdue Invoices
            $overdueCount = Invoice::where('status', 'unpaid')->where('due_date', '<', now()->today())->count();
            if ($overdueCount > 0) {
                $actionAlerts[] = [
                    'type' => 'danger',
                    'icon' => 'alert-circle',
                    'title' => 'Facturas Vencidas',
                    'count' => $overdueCount,
                    'text' => 'Hay ' . $overdueCount . ' facturas que superaron su fecha límite.',
                    'link' => route('billing.index', ['filter_status' => 'overdue'])
                ];
            }

            // 1b. Pending Payment Validations
            $pendingPayments = \App\Models\PaymentProof::where('status', 'pending')->count();
            if ($pendingPayments > 0) {
                $actionAlerts[] = [
                    'type' => 'warning',
                    'icon' => 'check-square',
                    'title' => 'Validar Pagos',
                    'count' => $pendingPayments,
                    'text' => 'Hay ' . $pendingPayments . ' comprobantes de Yappy/ACH por validar.',
                    'link' => route('billing.approvals')
                ];
            }

            // 2. Open Tickets
            $openTickets = Ticket::where('status', 'open')->count();
            if ($openTickets > 0) {
                $actionAlerts[] = [
                    'type' => 'warning',
                    'icon' => 'message-square',
                    'title' => 'Tickets de Soporte',
                    'count' => $openTickets,
                    'text' => $openTickets . ' solicitudes de clientes esperan respuesta.',
                    'link' => route('logistics.tickets')
                ];
            }

            // 3. New Pre-alerts
            $prealerts = Package::where('status', 'prealert')->count();
            if ($prealerts > 0) {
                $actionAlerts[] = [
                    'type' => 'info',
                    'icon' => 'bell',
                    'title' => 'Nuevas Pre-alertas',
                    'count' => $prealerts,
                    'text' => $prealerts . ' paquetes han sido anunciados por clientes.',
                    'link' => route('logistics.inventory', ['filter_status' => 'prealert'])
                ];
            }

            // 4. Stagnant Packages (Ready for pickup more than 5 days)
            $stagnant = Package::where('status', 'ready_for_pickup')->where('updated_at', '<', now()->subDays(5))->count();
            if ($stagnant > 0) {
                $actionAlerts[] = [
                    'type' => 'primary',
                    'icon' => 'clock',
                    'title' => 'Carga Estancada',
                    'count' => $stagnant,
                    'text' => $stagnant . ' paquetes listos no han sido retirados en 5+ días.',
                    'link' => route('logistics.counter')
                ];
            }

            // 5. New Customers (Last 48 hours)
            $newCustomersCount = Customer::where('created_at', '>=', now()->subHours(48))->count();
            if ($newCustomersCount > 0) {
                $actionAlerts[] = [
                    'type' => 'success',
                    'icon' => 'user-plus',
                    'title' => 'Nuevos Clientes',
                    'count' => $newCustomersCount,
                    'text' => $newCustomersCount . ' nuevos miembros se unieron en las últimas 48h.',
                    'link' => route('logistics.customers', ['filter' => 'new'])
                ];
            }

            // 6. Unverified Emails (Action required for accounts)
            $unverifiedCount = \App\Models\User::whereNull('email_verified_at')->where('role', 'customer')->count();
            if ($unverifiedCount > 0) {
                $actionAlerts[] = [
                    'type' => 'warning',
                    'icon' => 'mail',
                    'title' => 'Emails sin Validar',
                    'count' => $unverifiedCount,
                    'text' => $unverifiedCount . ' clientes aún no han confirmado su cuenta.',
                    'link' => route('logistics.customers', ['filter' => 'unverified'])
                ];
            }

            // 7. Inactive Customers (Registered but 0 packages)
            $inactiveCount = Customer::whereDoesntHave('packages')
                ->where('created_at', '<=', now()->subDays(7))
                ->count();

            if ($inactiveCount > 0) {
                $actionAlerts[] = [
                    'type' => 'secondary',
                    'icon' => 'user-minus',
                    'title' => 'Clientes Inactivos',
                    'count' => $inactiveCount,
                    'text' => $inactiveCount . ' clientes creados hace 7+ días no han enviado carga.',
                    'link' => route('logistics.customers', ['filter' => 'inactive'])
                ];
            }

            // 8. Money Leaks Alert
            if (($financialMetrics->leaks_count ?? 0) > 0) {
                $actionAlerts[] = [
                    'type' => 'danger',
                    'icon' => 'trending-down',
                    'title' => 'Fugas de Dinero',
                    'count' => $financialMetrics->leaks_count,
                    'text' => 'Detectamos ' . $financialMetrics->leaks_count . ' paquetes con margen negativo.',
                    'link' => route('logistics.inventory', ['filter' => 'leaks'])
                ];
            }

            $tenant = \App\Models\Tenant::find(session('tenant_id'));
            $currency = $tenant->settings_json['currency'] ?? 'USD';

            return view('livewire.dashboard', [
                'total_packages' => $packagesQuery->count(),
                'total_customers' => Customer::count(),
                'total_unpaid' => Invoice::where('status', 'unpaid')->sum('total'),
                'total_profit' => $financialMetrics->total_profit ?? 0,
                'projected_profit' => $projectedProfit,
                'avg_roi' => $financialMetrics->avg_roi ?? 0,
                'recent_packages' => $recent_packages,
                'warehouses' => Warehouse::all(),
                'chartData' => array_values($chartData),
                'revenueData' => array_values($revenueData),
                'costData' => array_values($costData),
                'projectionData' => array_values($projectionData),
                'warehouseLabels' => array_keys($warehouseUsage),
                'warehouseData' => array_values($warehouseUsage),
                'actionAlerts' => $actionAlerts,
                'currency' => $currency,
            ])->layout('components.layouts.app');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Dashboard Render Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
