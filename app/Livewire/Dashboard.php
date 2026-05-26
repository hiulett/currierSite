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

            $revenueData = array_fill(1, 12, 0);
            $projectionData = array_fill(1, 12, 0);

            // Advanced Projection Logic: Based on Revenue Trend + New Customers + Package Volume
            $totalMonthsWithData = 0;
            $sumRevenue = 0;
            $revenueData = array_fill(1, 12, 0);
            $projectionData = array_fill(1, 12, 0);

            foreach ($monthlyRevenue as $month => $total) {
                $monthIdx = (int)$month;
                $revenueData[$monthIdx] = $total;
                $sumRevenue += $total;
                if ($total > 0) $totalMonthsWithData++;
            }

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

            $tenant = \App\Models\Tenant::find(session('tenant_id'));
            $currency = $tenant->settings_json['currency'] ?? 'USD';

            return view('livewire.dashboard', [
                'total_packages' => $packagesQuery->count(),
                'total_customers' => Customer::count(),
                'total_unpaid' => Invoice::where('status', 'unpaid')->sum('total'),
                'recent_packages' => $recent_packages,
                'warehouses' => Warehouse::all(),
                'chartData' => array_values($chartData),
                'revenueData' => array_values($revenueData),
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
