---
name: make-crud
description: Scaffolds a complete Laravel CRUD feature for a given entity. Generates migration, model, repository, service, form request, controller, and feature test following project conventions. Use when user says "make crud", "generate crud", "create crud for", "scaffold resource", "tạo crud", or "tạo crud cho [entity]".
metadata:
  author: duongricky
  version: 1.0.0
  category: laravel
---

# make-crud

Scaffolds a complete Laravel CRUD feature following the project conventions defined in CLAUDE.md.

## IMPORTANT

Before starting, read `references/schema-format.md` to understand the expected input format.

If the user has not provided an input block, ask them to provide one using the format in `references/schema-format.md`.

---

## Step 1: Parse Input

Extract from the user's input block:
- **Entity**: singular PascalCase (e.g. `Product`)
- **Table**: auto-derive from entity if not provided (e.g. `products`)
- **Fields**: name, DB type, validation rules
- **Relationships**: type and related model
- **Options**: softDeletes (default: false), timestamps (default: true)

Derive the following names:
- Model: `App\Models\{Entity}`
- Controller: `App\Http\Controllers\{Entity}Controller`
- Service: `App\Services\{Entity}Service`
- Repository: `App\Repositories\{Entity}Repository`
- FormRequest (store): `App\Http\Requests\Store{Entity}Request`
- FormRequest (update): `App\Http\Requests\Update{Entity}Request`
- Test: `tests/Feature/{Entity}Test.php`

---

## Step 2: Create Migration

File: `database/migrations/{timestamp}_create_{table}_table.php`

Rules:
- Use `Schema::create('{table}', ...)`
- Map each field to its correct column method
- `foreignId` fields use `->constrained()->cascadeOnDelete()`
- Add `->nullable()` for nullable fields
- Add `$table->softDeletes()` if softDeletes option is true
- Add `$table->timestamps()` if timestamps option is true (default)
- Run: `docker compose exec app php artisan make:migration create_{table}_table`
  then fill in the generated file

---

## Step 3: Create Model

File: `app/Models/{Entity}.php`

Rules:
- Extend `Illuminate\Database\Eloquent\Model`
- Define `$fillable` from non-derived fields
- Define `$casts` for: boolean, decimal, date, timestamp, json, enum fields
- Add `use SoftDeletes` trait if softDeletes is true
- Add relationship methods matching the Relationships section
- Keep model thin — no business logic

---

## Step 4: Create Repository

File: `app/Repositories/{Entity}Repository.php`

Rules:
- Only create if the project already uses the repository pattern
- Check if `app/Repositories/` directory exists before creating
- Inject the Model via constructor
- Implement basic methods: `all()`, `find(int $id)`, `create(array $data)`, `update(int $id, array $data)`, `delete(int $id)`
- If softDeletes: add `forceDelete(int $id)` and `restore(int $id)`

---

## Step 5: Create Service

File: `app/Services/{Entity}Service.php`

Rules:
- Inject `{Entity}Repository` via constructor
- Delegate data access to repository
- Contains business logic only
- Methods: `getAll()`, `getById(int $id)`, `create(array $data)`, `update(int $id, array $data)`, `delete(int $id)`

---

## Step 6: Create Form Requests

Files:
- `app/Http/Requests/Store{Entity}Request.php`
- `app/Http/Requests/Update{Entity}Request.php`

Rules:
- `authorize()` returns `true`
- `rules()` returns validation rules from the input schema
- For Update request: wrap most rules with `sometimes` or use `nullable` variants
- Follow Laravel Form Request conventions

---

## Step 7: Create Controller

File: `app/Http/Controllers/{Entity}Controller.php`

Rules:
- Keep controller thin — delegate all logic to Service
- Inject `{Entity}Service` via constructor
- Implement RESTful methods: `index`, `store`, `show`, `update`, `destroy`
- All responses must follow the API response format from CLAUDE.md:
  - Success: `{ "success": true, "data": ... }`
  - Validation errors are handled automatically by Form Request (422)
  - Not found: return 404 with `{ "success": false, "message": "Not found." }`
  - Server errors: return 500 with `{ "success": false, "message": "Server error." }`
- Use `response()->json()` for all responses

---

## Step 8: Create Feature Test

File: `tests/Feature/{Entity}Test.php`

Rules:
- Extend `Tests\TestCase`
- Use `RefreshDatabase` trait
- Cover:
  - `test_can_list_{entities}()` — GET index returns 200
  - `test_can_create_{entity}()` — POST store with valid data returns 200
  - `test_create_{entity}_fails_validation()` — POST store with invalid data returns 422
  - `test_can_show_{entity}()` — GET show returns 200
  - `test_can_update_{entity}()` — PUT update returns 200
  - `test_can_delete_{entity}()` — DELETE destroy returns 200
- Follow the existing test style in the project

---

## Step 9: Register Route

Remind the user to add the resource route to `routes/api.php`:

```php
Route::apiResource('{entities}', {Entity}Controller::class);
```

---

## Verification

After generating all files, run:

```bash
docker compose exec app php artisan test --filter={Entity}Test
```

Report which files were created and any follow-up steps required.

---

## Troubleshooting

**Missing input block**: Ask user to provide schema using `references/schema-format.md` format.

**Repository pattern not in use**: Skip Step 4. Have Service use the Model directly.

**Enum fields**: Use Laravel's `Enum` cast with a PHP `enum` class in `app/Enums/`.

**belongsToMany**: Note that a pivot migration is needed — list the required table name but do not create it automatically without confirmation.
