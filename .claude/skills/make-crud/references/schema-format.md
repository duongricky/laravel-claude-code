# make-crud Input Format Guide

Use this format when calling `/make-crud`. Provide a block with the following structure.

---

## Full Example

```
Entity: Product

Fields:
  - name: string, required|string|max:255
  - price: decimal(10,2), required|numeric|min:0
  - category_id: foreignId, required|exists:categories,id
  - description: text, nullable|string|max:1000

Relationships:
  - belongsTo: Category

Options:
  softDeletes: true
```

---

## Field Reference

### Entity
- Singular PascalCase (e.g. `Product`, `OrderItem`, `UserProfile`)
- Table name is auto-derived: `Product` → `products`, `OrderItem` → `order_items`

### Fields

Format: `- field_name: db_type, validation_rules`

Supported DB types:
| Type | Example |
|---|---|
| string | `name: string` |
| text | `body: text` |
| integer | `quantity: integer` |
| bigInteger | `views: bigInteger` |
| decimal(p,s) | `price: decimal(10,2)` |
| boolean | `is_active: boolean` |
| date | `published_at: date` |
| timestamp | `started_at: timestamp` |
| foreignId | `user_id: foreignId` |
| json | `meta: json` |
| enum | `status: enum(pending,active,inactive)` |

Validation rules follow Laravel syntax: `required|string|max:255`

Common patterns:
- Required string: `required|string|max:255`
- Optional string: `nullable|string|max:1000`
- Required number: `required|numeric|min:0`
- Foreign key: `required|exists:table_name,id`
- Enum: `required|in:pending,active,inactive`

### Relationships

Format: `- type: RelatedModel`

Supported types:
- `belongsTo: Category`
- `hasMany: Comment`
- `belongsToMany: Tag`
- `hasOne: Profile`

### Options

| Option | Default | Description |
|---|---|---|
| softDeletes | false | Add `deleted_at` column and `SoftDeletes` trait |
| timestamps | true | Add `created_at` / `updated_at` columns |

---

## Minimal Example

```
Entity: Tag

Fields:
  - name: string, required|string|max:100
  - slug: string, required|string|max:100
```

---

## Notes

- Fields with `foreignId` type will automatically get a `constrained()` call in migration
- Enum fields require values listed: `status: enum(draft,published,archived)`
- `belongsToMany` relationships will note that a pivot table is needed but won't create it automatically
- The skill follows the API response format defined in CLAUDE.md
