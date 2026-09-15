<?php

namespace Tests\Feature;

use App\Livewire\Billing\ExpenseList;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
            'uuid' => (string) Str::uuid(),
            'name' => 'Logy Test Company',
            'subdomain' => 'logytest',
            'domain' => 'logytest.localhost',
            'status' => 'active',
            'settings_json' => ['currency' => 'USD'],
        ]);

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@logytest.localhost',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'tenant_id' => $this->tenant->id,
        ]);

        // email_verified_at no está en $fillable, se asigna con forceFill.
        $this->admin->forceFill(['email_verified_at' => now()])->save();

        // En tests la tabla de permisos se migra después del boot del provider,
        // así que registramos explícitamente la habilidad usada por la ruta.
        Gate::define('billing.view', fn () => true);

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
            'name' => 'Luz',
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
            'description' => 'Pago de luz del local central',
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
            'icon' => 'tool',
        ]);

        $category = ExpenseCategory::where('name', 'Mantenimiento de Vehículos')->first();

        // Delete the custom category
        Livewire::test(ExpenseList::class)
            ->call('deleteCategory', $category->id);

        $this->assertDatabaseMissing('expense_categories', [
            'id' => $category->id,
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
            'expense_date' => '2026-06-20',
        ]);

        Livewire::test(ExpenseList::class)
            ->call('deleteCategory', $category->id)
            ->assertSee('No se puede eliminar la categoría');

        $this->assertDatabaseHas('expense_categories', [
            'id' => $category->id,
        ]);
    }

    public function test_uploaded_attachment_can_be_retrieved_through_route()
    {
        $this->actingAs($this->admin);
        Storage::fake('public');

        Livewire::test(ExpenseList::class);
        $category = ExpenseCategory::first();

        Livewire::test(ExpenseList::class)
            ->set('expense_category_id', $category->id)
            ->set('amount', 99.99)
            ->set('expense_date', '2026-06-20')
            ->set('attachment', UploadedFile::fake()->create('comprobante.pdf', 10, 'application/pdf'))
            ->call('saveExpense')
            ->assertHasNoErrors();

        $expense = Expense::first();

        $this->assertNotNull($expense->attachment_path);
        $this->assertFalse(str_starts_with($expense->attachment_path, 'http'));
        Storage::disk('public')->assertExists($expense->attachment_path);

        $this->get(route('billing.expenses.attachment', $expense))->assertOk();
    }

    public function test_can_view_expense_attachment_through_authenticated_route()
    {
        $this->actingAs($this->admin);
        Storage::fake('public');

        $category = ExpenseCategory::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Luz',
            'icon' => 'zap',
        ]);

        $path = 'expenses/comprobante.pdf';
        Storage::disk('public')->put($path, 'contenido-pdf');

        $expense = Expense::create([
            'tenant_id' => $this->tenant->id,
            'expense_category_id' => $category->id,
            'amount' => 10.00,
            'expense_date' => '2026-06-20',
            'attachment_path' => $path,
        ]);

        $response = $this->get(route('billing.expenses.attachment', $expense));

        $response->assertOk();
        $response->assertHeader('content-disposition', 'inline; filename=comprobante.pdf');
        $this->assertStringContainsString('contenido-pdf', $response->streamedContent());
    }

    public function test_can_view_legacy_absolute_url_attachment()
    {
        $this->actingAs($this->admin);
        Storage::fake('public');

        $category = ExpenseCategory::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Luz',
            'icon' => 'zap',
        ]);

        $path = 'expenses/legacy.pdf';
        Storage::disk('public')->put($path, 'contenido-legacy');

        // Registros antiguos guardaban la URL absoluta completa.
        $expense = Expense::create([
            'tenant_id' => $this->tenant->id,
            'expense_category_id' => $category->id,
            'amount' => 10.00,
            'expense_date' => '2026-06-20',
            'attachment_path' => rtrim(config('app.url'), '/').'/storage/'.$path,
        ]);

        $this->get(route('billing.expenses.attachment', $expense))->assertOk();
    }

    public function test_cannot_view_attachment_from_another_tenant()
    {
        $otherTenant = Tenant::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Otro Tenant',
            'subdomain' => 'otrotenant',
            'status' => 'active',
            'settings_json' => ['currency' => 'USD'],
        ]);

        $category = ExpenseCategory::create([
            'tenant_id' => $otherTenant->id,
            'name' => 'Luz',
            'icon' => 'zap',
        ]);

        $foreignExpense = Expense::create([
            'tenant_id' => $otherTenant->id,
            'expense_category_id' => $category->id,
            'amount' => 10.00,
            'expense_date' => '2026-06-20',
            'attachment_path' => 'expenses/ajeno.pdf',
        ]);

        $this->actingAs($this->admin);

        $this->get(route('billing.expenses.attachment', $foreignExpense))->assertNotFound();
    }
}
