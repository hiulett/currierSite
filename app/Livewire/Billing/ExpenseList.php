<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Tenant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ExpenseList extends Component
{
    use WithPagination, WithFileUploads;

    // Filters
    public $search = '';
    public $category_filter = '';
    public $date_from = '';
    public $date_to = '';

    // Expense Form State
    public $expense_id = null;
    public $expense_category_id;
    public $amount;
    public $description;
    public $expense_date;
    public $payment_method = 'transferencia';
    public $reference_number;
    public $attachment;
    public $current_attachment_path;
    public $creating_or_editing = false;

    // Category Form State
    public $category_id = null;
    public $category_name;
    public $category_icon = 'tag';
    public $managing_categories = false;
    public $creating_or_editing_category = false;

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'description' => 'nullable|string|max:1000',
            'payment_method' => 'nullable|string|max:50',
            'reference_number' => 'nullable|string|max:100',
            'attachment' => 'nullable|file|max:4096', // 4MB Max
        ];
    }

    public function mount()
    {
        $tenant = Tenant::current();
        if (!$tenant) {
            return;
        }

        // Seed default categories if none exist for this tenant
        if (ExpenseCategory::count() === 0) {
            $defaults = [
                ['name' => 'Luz', 'icon' => 'zap'],
                ['name' => 'Agua', 'icon' => 'droplet'],
                ['name' => 'Teléfono', 'icon' => 'phone'],
                ['name' => 'Internet', 'icon' => 'globe'],
                ['name' => 'Planilla', 'icon' => 'users'],
                ['name' => 'Alquiler', 'icon' => 'home'],
                ['name' => 'Aduana', 'icon' => 'package'],
                ['name' => 'Aerolínea', 'icon' => 'send'],
                ['name' => 'Gasolina', 'icon' => 'truck'],
                ['name' => 'Otros', 'icon' => 'tag'],
            ];

            foreach ($defaults as $def) {
                ExpenseCategory::create([
                    'tenant_id' => $tenant->id,
                    'name' => $def['name'],
                    'icon' => $def['icon']
                ]);
            }
        }

        $this->expense_date = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    // Expense CRUD
    public function openCreateModal()
    {
        $this->resetForm();
        $this->expense_category_id = ExpenseCategory::first()?->id;
        $this->creating_or_editing = true;
    }

    public function editExpense($id)
    {
        $this->resetForm();
        $expense = Expense::findOrFail($id);

        $this->expense_id = $expense->id;
        $this->expense_category_id = $expense->expense_category_id;
        $this->amount = $expense->amount;
        $this->description = $expense->description;
        $this->expense_date = $expense->expense_date->format('Y-m-d');
        $this->payment_method = $expense->payment_method ?? 'transferencia';
        $this->reference_number = $expense->reference_number;
        $this->current_attachment_path = $expense->attachment_path;

        $this->creating_or_editing = true;
    }

    public function saveExpense()
    {
        $this->validate();

        $tenant = Tenant::current();
        if (!$tenant) return;

        $data = [
            'tenant_id' => $tenant->id,
            'expense_category_id' => $this->expense_category_id,
            'amount' => $this->amount,
            'description' => $this->description,
            'expense_date' => $this->expense_date,
            'payment_method' => $this->payment_method,
            'reference_number' => $this->reference_number,
        ];

        // Attachment Upload
        if ($this->attachment) {
            try {
                $filename = 'expense_' . $tenant->id . '_' . time() . '.' . $this->attachment->getClientOriginalExtension();
                $disk = !empty(config('filesystems.disks.s3.key')) ? 's3' : 'public';
                
                $path = $this->attachment->storeAs('expenses', $filename, [
                    'disk' => $disk,
                    'visibility' => 'public'
                ]);

                $data['attachment_path'] = Storage::disk($disk)->url($path);
            } catch (\Exception $e) {
                Log::error("Error subiendo comprobante de egreso: " . $e->getMessage());
                session()->flash('error', 'Error al subir el archivo comprobante.');
                return;
            }
        }

        if ($this->expense_id) {
            $expense = Expense::findOrFail($this->expense_id);
            $expense->update($data);
            session()->flash('success', 'Egreso actualizado correctamente.');
        } else {
            Expense::create($data);
            session()->flash('success', 'Egreso registrado exitosamente.');
        }

        $this->creating_or_editing = false;
        $this->resetForm();
    }

    public function deleteExpense($id)
    {
        $expense = Expense::findOrFail($id);
        
        // Remove attachment if exists
        if ($expense->attachment_path) {
            try {
                $disk = !empty(config('filesystems.disks.s3.key')) ? 's3' : 'public';
                $relativePath = str_replace(Storage::disk($disk)->url(''), '', $expense->attachment_path);
                Storage::disk($disk)->delete($relativePath);
            } catch (\Exception $e) {
                Log::error("Error eliminando adjunto de egreso: " . $e->getMessage());
            }
        }

        $expense->delete();
        session()->flash('success', 'Registro de egreso eliminado.');
    }

    private function resetForm()
    {
        $this->expense_id = null;
        $this->amount = null;
        $this->description = null;
        $this->expense_date = now()->format('Y-m-d');
        $this->payment_method = 'transferencia';
        $this->reference_number = null;
        $this->attachment = null;
        $this->current_attachment_path = null;
    }

    // Category CRUD
    public function openCategoryCreate()
    {
        $this->category_id = null;
        $this->category_name = '';
        $this->category_icon = 'tag';
        $this->creating_or_editing_category = true;
    }

    public function editCategory($id)
    {
        $category = ExpenseCategory::findOrFail($id);
        $this->category_id = $category->id;
        $this->category_name = $category->name;
        $this->category_icon = $category->icon ?? 'tag';
        $this->creating_or_editing_category = true;
    }

    public function saveCategory()
    {
        $this->validate([
            'category_name' => 'required|string|max:100',
            'category_icon' => 'required|string|max:50',
        ]);

        $tenant = Tenant::current();
        if (!$tenant) return;

        if ($this->category_id) {
            $category = ExpenseCategory::findOrFail($this->category_id);
            $category->update([
                'name' => $this->category_name,
                'icon' => $this->category_icon,
            ]);
            session()->flash('success', 'Categoría actualizada.');
        } else {
            ExpenseCategory::create([
                'tenant_id' => $tenant->id,
                'name' => $this->category_name,
                'icon' => $this->category_icon,
            ]);
            session()->flash('success', 'Categoría creada.');
        }

        $this->creating_or_editing_category = false;
        $this->category_name = '';
        $this->category_icon = 'tag';
    }

    public function deleteCategory($id)
    {
        $category = ExpenseCategory::findOrFail($id);
        
        // Prevent deleting if it has expenses, or re-assign to 'Otros'
        if ($category->expenses()->count() > 0) {
            session()->flash('error', 'No se puede eliminar la categoría porque contiene gastos asociados.');
            return;
        }

        $category->delete();
        session()->flash('success', 'Categoría eliminada correctamente.');
    }

    // Calculations for Cards
    private function getFinancialStats()
    {
        $startOfMonth = now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = now()->endOfMonth()->format('Y-m-d');
        $startOfYear = now()->startOfYear()->format('Y-m-d');

        $expensesThisMonth = Expense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $expensesYtd = Expense::where('expense_date', '>=', $startOfYear)->sum('amount');

        // Top Category
        $topCatQuery = Expense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
            ->selectRaw('expense_categories.name, sum(expenses.amount) as total')
            ->groupBy('expense_categories.name')
            ->orderBy('total', 'desc')
            ->first();

        $topCategoryName = $topCatQuery ? $topCatQuery->name : 'Ninguna';
        $topCategoryAmount = $topCatQuery ? $topCatQuery->total : 0;

        return [
            'month_total' => $expensesThisMonth,
            'ytd_total' => $expensesYtd,
            'top_category_name' => $topCategoryName,
            'top_category_amount' => $topCategoryAmount,
        ];
    }

    public function render()
    {
        $query = Expense::with('category');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhere('reference_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category_filter) {
            $query->where('expense_category_id', $this->category_filter);
        }

        if ($this->date_from) {
            $query->where('expense_date', '>=', $this->date_from);
        }

        if ($this->date_to) {
            $query->where('expense_date', '<=', $this->date_to);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(12);
        $categories = ExpenseCategory::all();
        $stats = $this->getFinancialStats();

        $tenant = Tenant::current();
        $currency = $tenant->settings_json['currency'] ?? 'USD';

        return view('livewire.billing.expense-list', [
            'expenses' => $expenses,
            'categories' => $categories,
            'stats' => $stats,
            'currency' => $currency
        ])->layout('components.layouts.app');
    }
}
