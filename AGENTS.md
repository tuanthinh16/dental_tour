# AGENTS.md

## Project Scope

This project is a Laravel-based travel website focused on:
- Public landing pages
- Destinations
- Tours and tour packages
- Tour itinerary and included/excluded items
- Consultation / lead forms
- Admin CMS
- MySQL
- Blade
- Tailwind CSS

Keep the architecture simple and maintainable.

Preferred structure:

```text
Controller -> Service -> Repository -> Model
```

Do not introduce additional architectural layers unless there is a clear and demonstrated need.

---

## Core Working Rules

### 1. Make the smallest possible change

Always make the minimum change required to complete the task.

Do not:
- Refactor unrelated files
- Rename unrelated variables, methods, classes, routes, tables, or folders
- Reformat unrelated code
- Change working behavior outside the requested scope
- Modify unrelated UI
- Update dependencies unless required
- Replace an existing implementation only because another approach looks cleaner

Before modifying a file, understand the existing implementation and preserve its conventions where reasonable.

### 2. Never revert existing work

Do not revert or overwrite changes that already exist in the project unless explicitly requested.

This includes:
- User changes
- Changes made by another agent
- Changes made by this agent in previous tasks

When existing code conflicts with the requested change, preserve both where possible.

If a safe merge is not possible, ask for confirmation before replacing existing behavior.

### 3. Do not guess missing requirements

If an implementation decision affects:
- Database schema
- Existing business logic
- Authentication
- Authorization
- API contracts
- URLs
- SEO
- Payment
- Data deletion
- Production behavior

and the required information is unclear, ask for confirmation before implementing.

Do not invent requirements to unblock yourself.

For small implementation details that do not affect behavior or public contracts, follow existing project conventions.

---

## Code Style

### 4. Comments

Do not add comments for obvious code.

Bad:

```php
// Get user
$user = User::find($id);
```

Only add comments when the logic is genuinely difficult to understand, for example:
- Non-obvious business rules
- Complex calculations
- Compatibility workarounds
- External provider quirks
- Important reasons behind unusual implementation choices

Comments should explain why, not restate what the code does.

### 5. No hardcoding

Do not hardcode values that belong in:
- Configuration
- Environment variables
- Database settings
- Constants
- Enums
- Existing application settings

Examples that must not be scattered through the code:
- Domain names
- API URLs
- Credentials
- Tokens
- Email addresses
- Phone numbers
- Currency defaults
- Date/time formats
- Status strings
- Role or permission names
- Storage paths
- Pagination limits

Use the appropriate existing mechanism:
- `.env`
- `config/*`
- database `settings`
- model constants / enums
- centralized common class

Do not move every literal into configuration. Only common, environment-specific, or business-significant values need centralization.

### 6. Never bypass cases

Do not solve failures by bypassing validation or requirements.

Do not:
- Disable SSL verification
- Disable CSRF
- Skip authentication
- Skip authorization
- Use `--ignore-platform-reqs`
- Suppress exceptions without handling them
- Remove validation to make a request pass
- Hardcode a successful result
- Add fake fallback data to hide a failure
- Modify vendor files
- Directly edit generated dependency files

Find and fix the root cause.

---

## Reuse and Common Logic

### 7. Reuse repeated behavior

Common behavior should be centralized when it is genuinely repeated.

Examples:
- Date/time formatting
- Currency formatting
- Price display
- Common status formatting
- Slug behavior
- File URL generation
- Common pagination behavior
- Common normalization
- Common query scopes

Prefer these locations in this order when appropriate:

1. Model scopes / accessors / casts
2. Existing Service
3. Dedicated small service/class
4. Laravel config
5. Blade component for repeated UI
6. Utility/helper only when no better natural owner exists

### 8. Avoid helper proliferation

Do not create generic helper files for every repeated line.

Avoid:
- `helpers.php` becoming a dumping ground
- Static utility classes containing unrelated methods
- Functions with unclear ownership

A reusable function should live close to the domain that owns the behavior.

For example:
- Tour price calculation -> `TourService`
- Active tour queries -> `Tour` model scope or `TourRepository`
- Currency formatting -> common formatter
- Date/time formatting -> common formatter
- Repeated button markup -> Blade component

### 9. Common time and formatting logic

Do not duplicate time/date/number/currency formatting across controllers and Blade views.

Create or use a common formatter when formatting rules are shared.

Formatting must respect configured timezone and locale where applicable.

Do not scatter calls such as:

```php
date(...)
number_format(...)
Carbon::parse(...)->format(...)
```

through many unrelated files when the output format is application-wide.

---

## Laravel Architecture

### 10. Controllers

Controllers should stay thin.

Controllers are responsible for:
- Receiving HTTP requests
- Calling validated Form Requests
- Invoking Services
- Returning views, redirects, or JSON

Do not put complex business logic or large Eloquent queries in controllers.

### 11. Services

Services contain business logic and coordinate workflows.

Use a Service when logic:
- Has multiple steps
- Operates across multiple models
- Represents a business action
- Requires a transaction
- Is shared by multiple controllers

Do not create a Service for trivial one-line model operations without a clear benefit.

### 12. Repositories

Repositories contain reusable or non-trivial database queries.

Do not create repository interfaces.

Do not create repositories purely to wrap every Eloquent method.

Simple model relationships and scopes may be used directly from Services when that produces clearer code.

### 13. Models

Models live under:

```text
app/Models
```

Models may contain:
- Relationships
- Casts
- Fillable fields
- Scopes
- Accessors/mutators
- SoftDeletes
- Small model-specific behavior

Do not put controller concerns or large workflows into Models.

### 14. Form validation

Use Laravel Form Request classes for non-trivial request validation.

Do not duplicate validation rules between controllers.

Validation error messages should be user-friendly where shown to public users.

### 15. Database

All primary business tables should normally include:

```php
$table->id();
$table->timestamps();
```

Use:

```php
$table->softDeletes();
```

for business data that should be recoverable.

Use `is_active` for simple active/inactive behavior instead of inventing unnecessary status states.

Use explicit status fields only when the entity has a real workflow.

Use:
- foreign keys
- appropriate indexes
- unique constraints where required
- decimal columns for money

Never use `float` or `double` for monetary values.

Do not alter an existing production-sensitive migration without confirming that it has not already been deployed. Prefer a new migration for schema changes.

### 16. Eloquent

Avoid N+1 queries.

Use eager loading when relationships are rendered in lists.

Prefer query scopes for repeated filters such as:

```php
Tour::active()
```

Do not retrieve unnecessary columns or entire datasets when only a subset is needed.

---

## Authentication and Authorization

### 17. Authentication

Do not weaken the existing authentication mechanism.

Never:
- Store plaintext passwords
- Store JWT tokens in logs
- Expose secrets to Blade or client-side JavaScript unnecessarily
- Log authorization headers

Use Laravel's hashing and authentication facilities.

### 18. Authorization

Every protected admin action must enforce the appropriate authorization.

Do not rely only on hiding buttons in the UI.

Server-side authorization remains mandatory.

Do not hardcode special user IDs such as:

```php
if ($user->id === 1)
```

Use roles and permissions.

---

## Logging and Errors

### 19. Logging

Important application events may be logged, including:
- Admin login success/failure
- Admin data mutations
- New consultation request
- Unexpected integration failure
- Critical exception

Do not log:
- Passwords
- JWT tokens
- API secrets
- Authorization headers
- Sensitive credentials

Logs should contain enough context to diagnose the issue without exposing secrets.

### 20. Error handling

Do not catch exceptions only to ignore them.

Bad:

```php
try {
    // ...
} catch (Throwable $e) {
}
```

If an exception is caught:
- Handle it meaningfully
- Log it when useful
- Return or throw an appropriate result

Prefer Laravel's global exception handling for unexpected failures.

---

## Frontend and Blade

### 21. Blade

Use Blade for server-rendered pages.

Avoid adding Vue, React, Alpine plugins, or additional frontend frameworks unless explicitly requested.

Use Blade components/partials for repeated UI.

Do not split every small piece of markup into a component. Extract only genuinely reusable pieces.

### 22. Tailwind CSS

Use existing Tailwind conventions in the project.

Do not:
- Add another CSS framework
- Introduce arbitrary visual systems unrelated to the current design
- Rebuild unrelated sections while changing one component

Keep UI responsive and mobile-first.

When modifying existing UI, preserve the established visual language unless redesign is explicitly requested.

### 23. Avoid generic AI-looking UI

Do not automatically add:
- Excessive gradient backgrounds
- Excessive rounded cards
- Random floating blobs
- Unnecessary glassmorphism
- Decorative badges everywhere
- Excessive shadows
- Generic dashboard layouts unrelated to the product

Favor clear hierarchy, restrained spacing, strong typography, real content structure, and practical travel-oriented presentation.

---

## Dependencies and Environment

### 24. Do not install system packages with Homebrew

Never execute:

```bash
brew install ...
brew upgrade ...
brew uninstall ...
brew link ...
```

or any other Homebrew command that modifies the system.

If a system dependency is required:
1. Explain exactly what is missing.
2. Explain why it is required.
3. Ask the user to install it manually.
4. Continue only after confirmation or after the dependency becomes available.

Read-only checks such as checking an existing executable are acceptable, but do not mutate Homebrew state.

### 25. Composer and npm dependencies

Do not add Composer or npm packages unless the requested feature genuinely requires them.

Before adding a package:
- Check if Laravel or the project already provides the functionality.
- Prefer existing dependencies.
- Avoid duplicate packages.
- Avoid abandoned or poorly maintained packages.

If a new major dependency changes project architecture or deployment requirements, ask for confirmation first.

Never modify files inside:

```text
vendor/
node_modules/
```

### 26. Environment configuration

Never commit or expose `.env` secrets.

When adding a required environment variable:
- Add the key to `.env.example`
- Use a safe placeholder
- Read it through Laravel config where appropriate

Avoid calling `env()` directly outside configuration files.

---

## Git and Existing Changes

### 27. Preserve working tree changes

Assume uncommitted changes may belong to the user.

Before making broad changes, inspect the relevant diff when possible.

Do not run destructive commands such as:

```bash
git reset --hard
git checkout .
git clean -fd
git restore .
```

Do not revert unrelated files.

Do not rewrite history.

### 28. Do not overwrite without inspecting

Before replacing an existing file:
- Read it first
- Understand its current role
- Preserve unrelated behavior

Prefer patching the smallest relevant section.

---

## Tests and Verification

### 29. Verify the requested change

After implementation, run the smallest relevant verification available.

Examples:
- Targeted PHPUnit/Pest test
- Relevant Laravel test class
- `php artisan route:list`
- `php artisan config:clear`
- Syntax check
- Build frontend assets when frontend code changed

Do not claim a change works if it has not been verified.

If verification cannot be performed, state exactly what was not verified.

### 30. Do not fix unrelated failures

If tests reveal unrelated existing failures:
- Report them
- Do not modify unrelated code to make the suite green unless asked

---

## Security

### 31. Public input is untrusted

Validate and sanitize public input appropriately.

Use:
- Eloquent/query bindings
- Laravel validation
- Blade escaping

Avoid raw SQL unless necessary.

If raw SQL is needed, bind parameters.

Do not output unescaped user content using `{!! !!}` unless the field is explicitly trusted and sanitized.

### 32. Uploads

For uploaded media:
- Validate MIME/type
- Validate size
- Generate safe filenames
- Store using Laravel Storage
- Do not trust the original filename
- Do not allow executable uploads into public web paths

---

## Travel Domain Rules

### 33. Core domain

Current project domain is travel and tour consultation.

Main concepts include:
- Destinations
- Tour Categories
- Tours
- Tour Itineraries
- Included Items
- Excluded Items
- Consultation Requests
- Pages
- Media
- Settings

Do not introduce unrelated dental or clinic terminology.

### 34. Tour pricing

Tour prices are business data.

Do not:
- Calculate them from presentation text
- Hardcode currencies in views
- Infer discounts from labels

Use database/configured values.

If `original_price` and `price` are available, presentation logic may display the discount, but the stored monetary values remain the source of truth.

### 35. Lead / consultation flow

The current product is primarily:

```text
Landing -> Tour -> Consultation Request -> Admin Follow-up
```

Do not introduce booking engines, payment flows, inventory systems, flight APIs, hotel inventory, or CRM workflows unless explicitly requested.

---

## Seeder Rules

### 36. Initial admin seed

Initial bootstrap credentials may exist only for first-time setup.

The initial admin seeder:
- Must be idempotent
- Must check whether the admin already exists
- Must not be required by runtime
- May be deleted after successful initialization

Do not recreate or reset the admin password on subsequent seed runs.

Demo seeders must not overwrite real user/admin data.

---

## Decision Priority

When multiple implementations are possible, choose in this order:

1. Correctness
2. Preserve existing behavior
3. Security
4. Smallest change
5. Existing project conventions
6. Maintainability
7. Reusability
8. Performance
9. Elegance

Do not make the code more abstract merely because abstraction is possible.

---

## Before Finishing a Task

Confirm:
- Only relevant files were changed
- Existing unrelated changes were preserved
- No hardcoded workaround was introduced
- No authorization or validation was bypassed
- Common logic was reused where appropriate
- No unnecessary helper/package/layer was added
- No Homebrew package was installed or modified
- The requested behavior was verified
- Any unresolved ambiguity or unverified item is clearly stated
