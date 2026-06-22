<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Livewire\Billing\ExpenseList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Logy Test Company',
            'subdomain' => 'logytest',
            'domain' => 'logytest.localhost',
            'status' => 'active',
            'settings_json' => ['currency' => 'USD']
        ]);

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@logytest.localhost',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'tenant_id' => $this->tenant->id,
        ]);

        session(['tenant_id' => $this->tenant->id]);
    }

    public function test_expense_list_seeds_default_categories_on_mount()
    {
        $this->actingAs($this->admin);

        $this->assertEquals(0, ExpenseCategory::count());

        Livewire::test(ExpenseList::class);

        // Standard categories should be auto-seeded
        $this->assertGreaterThan(0, ExpenseCategory::count());
        $this->assertDatabaseHas('expense_categories', [
            'tenant_id' => $this->tenant->id,
            'name' => 'Luz'
        ]);
    }

    public function test_can_create_expense_via_livewire()
    {
        $this->actingAs($this->admin);

        Livewire::test(ExpenseList::class);
        $category = ExpenseCategory::first();

        Livewire::test(ExpenseList::class)
            ->set('expense_category_id', $category->id)
            ->set('amount', 250.00)
            ->set('expense_date', '2026-06-20')
            ->set('description', 'Pago de luz del local central')
            ->set('payment_method', 'transferencia')
            ->call('saveExpense')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('expenses', [
            'tenant_id' => $this->tenant->id,
            'expense_category_id' => $category->id,
            'amount' => 250.00,
            'description' => 'Pago de luz del local central'
        ]);
    }

    public function test_can_create_and_delete_custom_category()
    {
        $this->actingAs($this->admin);

        Livewire::test(ExpenseList::class)
            ->set('category_name', 'Mantenimiento de Vehículos')
            ->set('category_icon', 'tool')
            ->call('saveCategory')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('expense_categories', [
            'tenant_id' => $this->tenant->id,
            'name' => 'Mantenimiento de Vehículos',
            'icon' => 'tool'
        ]);

        $category = ExpenseCategory::where('name', 'Mantenimiento de Vehículos')->first();

        // Delete the custom category
        Livewire::test(ExpenseList::class)
            ->call('deleteCategory', $category->id);

        $this->assertDatabaseMissing('expense_categories', [
            'id' => $category->id
        ]);
    }

    public function test_cannot_delete_category_with_associated_expenses()
    {
        $this->actingAs($this->admin);

        Livewire::test(ExpenseList::class);
        $category = ExpenseCategory::first();

        $expense = Expense::create([
            'tenant_id' => $this->tenant->id,
            'expense_category_id' => $category->id,
            'amount' => 100.00,
            'expense_date' => '2026-06-20'
        ]);

        Livewire::test(ExpenseList::class)
            ->call('deleteCategory', $category->id)
            ->assertSee('No se puede eliminar la categoría');

        $this->assertDatabaseHas('expense_categories', [
            'id' => $category->id
        ]);
    }
}
