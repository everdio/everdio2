# Everdio2 PHP Framework

**A Revolutionary Low-Code Framework for Rapid Web Development**

Version: 2.0 | License: MIT | Author: Everdio Contributors

---

## Table of Contents

1. [Overview](#overview)
2. [Quick Start](#quick-start)
3. [Core Concepts](#core-concepts)
4. [Architecture](#architecture)
5. [API Reference](#api-reference)
6. [Examples](#examples)
7. [Guides](#guides)
8. [Troubleshooting](#troubleshooting)

---

## Overview

### What is Everdio2?

Everdio2 is a **unified data abstraction framework** that eliminates boilerplate code through:

- **Single Interface** across MySQL, SQLite, XML, APIs, and more
- **Low-Code DSL** based on URL syntax for declaring business logic
- **Declarative Configuration** using INI files instead of imperative code
- **Automatic Code Generation** from HTML templates to PHP classes
- **Process-Based Threading** with stateful cloning for true parallelism
- **Type-Safe Operations** with comprehensive validation at every boundary

### Why Everdio2?

```php
// Traditional Framework
function getUserEmails() {
    $users = DB::where('status', 'active')->get();
    $emails = [];
    foreach ($users as $user) {
        $emails[] = $user->email;
        // Handle errors
        // Validate data
        // Map properties
    }
    return $emails;
}

// Everdio2
[get_user_emails]
User[] = "finder:?path=users[active]/restore:";
```

**Key Benefits:**
- ✅ **70% less boilerplate** code
- ✅ **Declarative** configuration (easier to read)
- ✅ **Type-safe** validation at every step
- ✅ **Cross-platform** (works with any data source)
- ✅ **Built-in threading** (true parallelism)
- ✅ **Zero ORM overhead** (direct mapping)

---

## Quick Start

### Installation

```bash
git clone https://github.com/everdio/everdio2.git
cd everdio2
chmod +x everdio
```

### Basic Example

**1. Create a controller (www.php):**

```php
<?php
if ($this instanceof \Component\Core\Adapter\Wrapper\Controller) {
    $this->auto("process");
}
```

**2. Define logic (www.ini):**

```ini
[process]
Application_MyApp[] = "finder:?path=data/users/restore:";
Application_MyApp[] = "echo:?v={{result}}";
```

**3. Run it:**

```bash
./everdio --application/myapp
```

### HTTP Example

**1. Create HTML template (www/index.html):**

```html
<!DOCTYPE html>
<html>
  <body>
    <div id="wrapper">
      <div id="main">
        <h1>Welcome</h1>
      </div>
    </div>
  </body>
</html>
```

**2. Generate classes (library/editor.ini):**

```ini
[deploy]
Modules_Node_Adapter_Document_Html[] = "__set:?p=document&v=www/index.html";
Modules_Node_Adapter_Document_Html[] = "__set:?p=namespace&v=Application\MyApp\Library\Editor";
Modules_Node_Adapter_Document_Html[model] = "deploy:";
```

**3. Run:**

```bash
./everdio --application/myapp/library/editor
```

Generated classes mirror your HTML structure:
- `Application\MyApp\Library\Editor\Html`
- `Application\MyApp\Library\Editor\Html\Body`
- `Application\MyApp\Library\Editor\Html\Body\Div`
- etc.

**4. Use in business logic (www.ini):**

```ini
[render]
Application_MyApp_Library_Editor_Html_Body_Div[] = "store:?p[className]=active";
Application_MyApp_Library_Editor_Html_Body_Div[mapper] = "save:";
Application_MyApp[] = "saveHTML://die";
```

---

## Core Concepts

### 1. The URL Execution Protocol

Everdio2's power comes from treating **URLs as executable code**.

**Syntax:**

```
[method]:?[query_params]//[function]/?[more_params]

Components:
- method     : Instance method to call (before :)
- query      : URL query parameters (after ?)
- function   : Global function to call (after //)
- params     : Additional parameters
```

**Examples:**

```ini
; Simple method call
finder:?path=users/restore:
→ $this->finder("path" => "users/restore")

; Method with chain
store:?p[status]=active//die/?
→ die($this->store(p[status]="active"))

; Nested substitution
finder:?path=users[id={{id}}]/restore:
→ $this->finder("path" => "users[id=123]/restore")

; Global function call
//sprintf/?format=User:%s&name={{name}}
→ sprintf("User:%s", "John")

; Control flow
//die/
→ die()

; Special cases
//print_r/?value={{data}}
→ print_r($data)

//eval/?code=2+2
→ eval("return 2+2;")
```

### 2. Parameter Validation

Every parameter is wrapped in a **Validation object** with type checking:

```php
// In framework code
$parameter = new Validation(
    $value,                          // default value
    [new Validator\IsString],        // validators
    Validation::STRICT               // mode
);

// In INI
parameter = "string_value";
parameter = 123;
parameter[] = "array_value";
```

**Common Validators:**
- `IsString` - Must be string
- `IsInteger` - Must be integer
- `IsArray` - Must be array
- `IsUrl` - Must be valid URL
- `IsEmail` - Must be valid email
- `IsFile` - Must be existing file
- `IsDir` - Must be existing directory
- Custom validators can extend `\Component\Validator`

### 3. The Auto Trait - Declarative Control Flow

The `Auto` trait processes INI sections and executes callbacks with built-in control flow:

**[continue] - Skip if condition matches:**

```ini
[process]
Application_MyApp[result] = "finder:?path=data/users/restore:";

[continue]
Application_MyApp[result] = null
→ If result is null, skip remaining operations
```

**[break] - Stop if condition matches:**

```ini
[break]
Application_MyApp[result] = false
→ If result is false, stop execution
```

**[is] - Continue only if condition matches:**

```ini
[is]
Application_MyApp[result] = true
→ Continue only if result equals true
```

**[isnot] - Continue only if condition doesn't match:**

```ini
[isnot]
Application_MyApp[result] = error
→ Continue only if result is not "error"
```

**[foreach] - Loop over collection:**

```ini
[foreach]
Application_MyApp[users] = "process_user:?user_id={{id}}"
→ For each user, call process_user callback
   Available: {{id}} (key), {{user}} (value)
```

**[hidden] - Hide from debug output:**

```ini
[hidden]
Application_MyApp[password] = 1
→ Don't show in debug HTML comments
```

### 4. Mapper Classes - Unified Data Access

Mappers provide a **single interface** for different data sources:

```php
// All mappers have these methods:
$mapper->find(array $validations = []);        // Find one
$mapper->findAll(array $validations = []);     // Find many
$mapper->save();                                // Persist
$mapper->delete();                              // Remove

// Data source doesn't matter:
// - MySQL: SELECT ... FROM
// - SQLite: SELECT ... FROM
// - XML: XPath query
// - BaseX: Query XML database
// - API: GET request
```

**Built-in Mappers:**

| Source | Module | Query Type |
|--------|--------|-----------|
| MySQL/SQLite | `Modules\Table` | SQL |
| DOMDocument | `Modules\Node` | XPath |
| XML DB | `Modules\BaseX` | XPath |
| Memcached | `Modules\Memcached` | Key-value |
| OpenAI | `Modules\OpenAi` | REST |

### 5. Threading - Process-Based Concurrency

Execute callbacks in parallel with **complete state preservation**:

```ini
; Create thread
[send_emails]
Application_MyApp[pending] = "finder:?path=users[pending]/restore:";

[foreach]
Application_MyApp[pending] = "thread:?callback=sendEmail:?user_id={{id}}&queue=true";

; Retrieve results
Application_MyApp[results] = "pool:?threads={{pool}}";
```

**What happens internally:**

```
1. Main process exports current state
2. Serialize all parameters
3. Generate temporary PHP file (/tmp/uuid.php)
4. Execute as subprocess with timeout
5. Child inherits complete parent state (DB, auth, config, etc.)
6. Child executes callback in isolation
7. Results written to output file
8. Main process polls for completion
9. Auto-cleanup of temp files
```

---

## Architecture

### High-Level Flow

```
Request
  ↓
www.php (entry point)
  ↓
$this->auto("section")
  ↓
Auto trait processes [section] in www.ini
  ↓
For each Object_Class[key]:
  ├─ Convert to namespace: Object\Class
  ├─ Instantiate class
  ├─ Execute callback URL
  └─ Apply control flow (continue/break/is/foreach)
  ↓
Results stored in $this->auto property
  ↓
Return response (HTML/JSON/etc.)
```

### Class Hierarchy

```
Component\Core (base parameter management)
  ├─ Component\Core\Adapter (basic adapter)
  │  ├─ Component\Core\Adapter\Mapper (data source abstraction)
  │  │  ├─ Modules\Table (SQL)
  │  │  └─ Modules\Node (XPath)
  │  │
  │  └─ Component\Core\Adapter\Wrapper (threading support)
  │     └─ Component\Core\Adapter\Wrapper\Controller
  │        └─ Component\Core\Adapter\Wrapper\Controller\Model
  │           └─ Component\Core\Adapter\Wrapper\Controller\Model\Http
  │
  └─ Modules\* (third-party integrations)
     ├─ Modules\OpenAi
     ├─ Modules\Memcached
     ├─ Modules\BaseX
     └─ etc.
```

### Threading Architecture

**Process Cloning with Stateful Execution:**

```
Parent Process
  ├─ State: {user, db, cache, config, ...}
  └─ Call: $this->thread("callback:?args")
     ↓
     1. Export state to Thread model
     2. Serialize parameters (hydrate)
     3. Generate PHP code from template
     4. Write to /tmp/{uuid}.php
     5. Lint check
     6. Execute as subprocess
     ↓
Child Process (/tmp/{uuid}.php)
  ├─ Load autoloader
  ├─ Create controller instance
  ├─ Import state (deserialize)
  ├─ Execute callback via low-code URL
  ├─ Write output to /tmp/{uuid}.out
  └─ Auto-cleanup (delete .php file)
     ↓
Main Process
  └─ Poll for completion
     ├─ Check if .php deleted
     ├─ Read from .out
     ├─ Clean up .out
     └─ Return results
```

---

## API Reference

### Core Classes

#### Component\Core\Adapter\Mapper

**Universal data access interface.**

**Properties:**
- `$mapping` - Field mappings (parameter => field)
- `$primary` - Primary key fields
- `$keys` - Foreign key fields
- `$parents` - Parent relationships
- `$label` - Display field
- `$resource` - Data source identifier

**Methods:**

```php
// Query
find(array $validations = []);                    // Find one record
findAll(array $validations = [], array $orderby = [], int $position = 0, int $limit = 0);

// Modification
save();                                           // Persist changes
delete();                                         // Remove record

// Introspection
hasField(string $field): bool;                    // Check field exists
getField(string $parameter): string;              // Get field name
isPrimary(string $parameter): bool;               // Is primary key?
isKey(string $parameter): bool;                   // Is foreign key?
getMapping(): array;                              // Get all mappings
getHumanized(array $types = ["IsString"]): string; // Get display value
```

#### Component\Core\Adapter\Wrapper\Controller

**Base HTTP/CLI controller.**

**Properties:**
- `$path` - Application base path
- `$routing` - Current route
- `$request` - Request parameters
- `$debug` - Debug parameter name
- `$method` - HTTP method (get/post/etc)
- `$hostname` - Server hostname
- `$remote` - Client IP

**Methods:**

```php
// Execution
auto(string $section, string $property = "auto", string $library = "aliases"): void;
execute(string $path, array $request = []);      // Execute route
dispatch(string $path);                           // Load and execute file

// Threading
thread(string $callback, bool $queue = false, int $sleep = 0, int $timeout = 300): string;
retrieve(string $thread);                         // Get single result
pool(array $threads);                             // Get multiple results

// Utilities
finder(string $path, array $arguments = []): mixed; // Navigate properties
callback(string $url, array $arguments = []);    // Execute URL
getCallbacks(string $value): string;             // Process templates
isRoute(string $route): bool;                     // Check current route
```

#### Component\Validation

**Type-safe parameter wrapper.**

```php
new Validation(
    $default_value,                    // Default value
    [new Validator\IsString, ...],    // Validators to apply
    Validation::STRICT                 // Mode: STRICT/NORMAL
);
```

**Methods:**

```php
execute();                             // Get validated value
set($value);                           // Set and validate
isValid(): bool;                       // Check if valid
getTypes(): array;                     // Get validators
```

### Low-Code DSL Reference

#### URL Callback Syntax

```
method:?param1=value1&param2=value2//function/?param3=value3

Parsing:
├─ method        = Everything before first ":"
├─ function      = Everything after "//"
└─ parameters    = Query string (after "?")

Execution:
1. Parse query string to array
2. Hydrate special values ({{...}})
3. If method exists: call $this->method(...$args)
4. If function exists AND method result: call function(result)
5. Else if function exists: call function(...$args)
6. Return result
```

#### Template Substitution

```ini
; Inline substitution
callback = "mail:?to={{user/email}}&subject={{subject}}";
→ Replaces {{user/email}} with finder result
→ Replaces {{subject}} with parameter value

; Nested calls
callback = "store:?data={{finder:?path=api/data/restore:}}";
→ Executes finder callback
→ Uses result in store callback

; Path traversal
{{auto/Application_MyApp/result/0}}
→ Navigates: auto → Application_MyApp → result → [0]
```

#### Control Flow Operations

```ini
[continue]
ClassName[property] = halt_value
→ Skip if property == halt_value

[break]
ClassName[property] = stop_value
→ Stop if property == stop_value

[is]
ClassName[property] = required_value
→ Continue only if property == required_value

[isnot]
ClassName[property] = forbidden_value
→ Continue only if property != forbidden_value

[foreach]
ClassName[collection] = "callback:?item={{value}}&key={{key}}"
→ Loop over collection items

[hidden]
ClassName[property] = 1
→ Hide from debug output
```

---

## Examples

### Example 1: Database Query (MySQL)

**www.ini:**

```ini
[get_active_users]
Application_MyApp[] = "finder:?path=users[status=active]/restore:";
Application_MyApp[users] = "//json_encode/?data={{auto/results}}";
```

**What happens:**

```
1. finder finds User mappers where status="active"
2. Calls find() on User mapper
3. User mapper generates: SELECT * FROM users WHERE status='active'
4. Returns array of User objects
5. json_encode converts to JSON
6. Output JSON response
```

### Example 2: XML DOM Manipulation

**Generate classes:**

```bash
./everdio --application/myapp/library/editorial
```

**www.ini:**

```ini
[modify_dom]
Application_MyApp_Library_Editorial_Html_Body_Div[] = "store:?p[className]=highlight";
Application_MyApp_Library_Editorial_Html_Body_Div[mapper] = "save:";
Application_MyApp_Library_Editorial_Html[] = "saveHTML://die";
```

**What happens:**

```
1. Find Html_Body_Div element
2. Store className="highlight" in its properties
3. Save back to DOM
4. Output modified HTML
5. Exit
```

### Example 3: Parallel Email Processing

**www.ini:**

```ini
[send_emails]
Application_MyApp[pending_users] = "finder:?path=users[email_pending]/restore:";

[foreach]
Application_MyApp[pending_users] = "thread:?callback=sendEmail:?user_id={{id}}&queue=true";

[email_sent]
Application_MyApp[results] = "pool:?threads={{pool}}";
Application_MyApp[] = "echo:?v={{results}}";
```

**What happens:**

```
1. Find all users pending email
2. For each user:
   - Create thread with user context
   - Thread executes: sendEmail(user_id=X)
   - Results written to file
3. Main process polls for completion
4. Gather all results
5. Output combined response
```

**Thread execution (auto-generated /tmp/uuid.php):**

```php
<?php
declare(ticks = 1);

function terminate() {
    if (is_file(__FILE__)) {
        unlink(__FILE__);  // Self-cleanup
    }
}

register_shutdown_function("terminate");
include_once("/path/to/everdio.php");

try {
    $controller = new Application\MyApp;
    $controller->import([
        "user" => new Validation([...], [...]),
        "database" => new Validation(PDO, [...]),
        "cache" => new Validation(Memcached, [...]),
        // All parent state
    ]);
    
    pcntl_async_signals(true);
    pcntl_signal(SIGTERM, [$controller, "terminate"], false);
    pcntl_signal(SIGINT, [$controller, "terminate"], false);
    
    echo $controller->callback("sendEmail:?user_id=123");
    
} catch (Exception|Error $ex) {
    file_put_contents(__DIR__ . "/uuid.err", $ex->getMessage());
}

exit;
```

### Example 4: API Integration (OpenAI)

**www.ini:**

```ini
[chat_completion]
Modules_OpenAi[url] = "https://api.openai.com/v1/chat/completions";
Modules_OpenAi[key] = "sk-...";
Modules_OpenAi[model] = "gpt-4";
Modules_OpenAi[messages] = [
    {"role": "user", "content": "{{prompt}}"}
];
Modules_OpenAi[] = "getResponse:";
Application_MyApp[] = "echo:?v={{response}}";
```

**What happens:**

```
1. Create OpenAI adapter with credentials
2. Call getResponse()
3. Makes HTTP POST to OpenAI
4. Parses JSON response
5. Returns model response
6. Output to user
```

---

## Guides

### Guide 1: Creating a Database-Driven Application

**Step 1: Database Setup**

```sql
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  email VARCHAR(255),
  status VARCHAR(50),
  created_at TIMESTAMP
);
```

**Step 2: Create User Mapper**

```php
// application/myapp/library/User.php
namespace Application\MyApp\Library;

class User extends \Component\Core\Adapter\Mapper 
    implements \Component\Core\Adapter\Mapper\Base {
    
    use \Modules\Table;
    
    public function __construct(array $values = []) {
        parent::__construct([
            "resource" => new \Component\Validation("users", [...]),
            "mapping" => new \Component\Validation([
                "id" => "id",
                "name" => "name",
                "email" => "email",
                "status" => "status",
                "created_at" => "created_at"
            ], [...]),
            "primary" => new \Component\Validation(["id"], [...]),
            "label" => new \Component\Validation("name", [...]),
        ]);
        $this->store($values);
    }
}
```

**Step 3: Define Business Logic (www.ini)**

```ini
[users_active]
Application_MyApp_Library_User[] = "finder:?path=users[status=active]/restore:";

[users_by_email]
Application_MyApp_Library_User[email] = "{{email}}";
Application_MyApp_Library_User[] = "find:";

[create_user]
Application_MyApp_Library_User[name] = "{{name}}";
Application_MyApp_Library_User[email] = "{{email}}";
Application_MyApp_Library_User[status] = "pending";
Application_MyApp_Library_User[] = "save:";
```

**Step 4: Use in Controller**

```php
// www.php
<?php
if ($this instanceof \Component\Core\Adapter\Wrapper\Controller) {
    $action = $this->routing;
    
    if ($action == "/users/active") {
        $this->auto("users_active");
        echo json_encode($this->auto->restore());
    }
}
```

### Guide 2: Building an HTML Template Application

**Step 1: Create HTML Template**

```html
<!-- www/template.html -->
<!DOCTYPE html>
<html>
  <head>
    <title>Dashboard</title>
  </head>
  <body>
    <div id="wrapper">
      <header id="header">
        <h1>Dashboard</h1>
      </header>
      <main id="content">
        <section id="products">
          <article id="product-item">
            <h3>Product Name</h3>
            <p>Product Description</p>
          </article>
        </section>
      </main>
    </div>
  </body>
</html>
```

**Step 2: Generate Classes**

Create `library/template.ini`:

```ini
[deploy]
Modules_Node_Adapter_Document_Html[] = "__set:?p=document&v=www/template.html";
Modules_Node_Adapter_Document_Html[] = "__set:?p=namespace&v=Application\MyApp\Library\Template";
Modules_Node_Adapter_Document_Html[model] = "deploy:";
```

Run:

```bash
./everdio --application/myapp/library/template
```

**Step 3: Use Generated Classes (www.ini)**

```ini
[render_dashboard]
Application_MyApp_Library_Template_Html_Body_Header[] = "store:?p[dataRole]=banner";
Application_MyApp_Library_Template_Html_Body_Header[mapper] = "save:";

Application_MyApp_Library_Template_Html_Body_Main_Section[products] = "finder:?path=api/products/restore:";

[foreach]
Application_MyApp_Library_Template_Html_Body_Main_Section_Article[product] = "renderProduct:?product={{value}}";

[final]
Application_MyApp_Library_Template_Html[] = "saveHTML://die";
```

### Guide 3: Multi-Threaded Data Processing

```ini
[process_batch]
; Get items to process
Application_MyApp[items] = "finder:?path=items[pending]/restore:";

; Process each in parallel
[foreach]
Application_MyApp[items] = "thread:?callback=processItem:?item_id={{id}}&queue=true&timeout=60";

; Wait for all to complete
[collect_results]
Application_MyApp[results] = "pool:?threads={{pool}}";

; Store results
[store_results]
Application_MyApp[] = "saveBatch:?results={{results}}";
```

---

## Troubleshooting

### Common Issues

#### 1. "INVALID_PARAMETER" Error

**Problem:** Parameter not defined in class.

```
Exception: INVALID_PARAMETER MyClass->undefined_param
```

**Solution:** Check parameter name in class constructor:

```php
// In class
public function __construct() {
    parent::__construct([
        "valid_param" => new Validation(false, [...]),
    ]);
}

// In INI
Application_MyClass[valid_param] = "value";  // ✓ Correct
Application_MyClass[invalid_param] = "value"; // ✗ Error
```

#### 2. "BAD_METHOD_CALL" Error

**Problem:** Method doesn't exist or wrong signature.

```
Exception: BAD_METHOD_CALL MyClass->nonexistent_method()
```

**Solution:** Verify method exists and is callable:

```php
class MyClass {
    public function my_method() {  // ✓ Public and accessible
        return "result";
    }
}

// In INI
Application_MyClass[] = "my_method:";           // ✓ Correct
Application_MyClass[] = "nonexistent_method:"; // ✗ Error
```

#### 3. "PARSE_ERROR" in Threading

**Problem:** Generated thread PHP has syntax error.

```
Exception: PARSE_ERROR ... FOR_THREAD /tmp/uuid.php
```

**Solution:** Check for special characters in serialized parameters. Use debug mode:

```bash
./everdio --application/myapp --debug=1
```

This shows all HTML comments with execution trace, including thread generation.

#### 4. Thread Not Completing

**Problem:** Thread hangs or takes too long.

```php
// Increase timeout
$this->thread("callback:?args", false, 0, 600);  // 600 second timeout
```

**Solution:** 
- Check for infinite loops in callback
- Verify database connections work in thread context
- Check /tmp for orphaned .php files
- Review .err files for exceptions

#### 5. Serialization Issues

**Problem:** Complex objects can't be serialized.

```
Error during parameter hydration/dehydration
```

**Solution:** Use simpler data types:

```ini
; Bad - Complex objects
callback = "mail:?user={{user_object}}";

; Good - Serializable data
callback = "mail:?user_id={{user/id}}&email={{user/email}}";
```

#### 6. XPath Queries Return Empty

**Problem:** Node mapper can't find elements.

```php
$mapper->findAll([new Node\Via($this)]);  // Returns empty
```

**Solution:** Verify XPath:

```php
// Debug XPath
$xpath = new DOMXPath($dom);
$nodes = $xpath->query("//section[@id='content']");
echo "Found: " . $nodes->count() . " nodes";
```

Check:
- Element exists in DOM
- Attributes match exactly
- No namespace issues
- Case sensitivity

#### 7. Debug Mode Not Showing Output

**Problem:** HTML comments not appearing.

```html
<!-- [section_name] -->  ← Not showing
```

**Solution:** Enable debug parameter:

```
http://localhost/?debug=1
./everdio --application/myapp --debug=1
```

Verify debug parameter is set in controller:

```php
$this->debug = "debug";  // Name of debug parameter
```

---

## Performance Optimization

### 1. Query Optimization

```ini
; Bad - Fetches all users then filters in PHP
[users]
Application_MyApp[users] = "finder:?path=users/restore:";

[filter_users]
; Filter in database/XML source (generates WHERE/predicate)
[users_optimized]
Application_MyApp[users] = "finder:?path=users[status=active&role=admin]/restore:";
```

### 2. Caching with Memcached

```ini
[get_products]
; Check cache first
Application_MyApp[cached] = "memcached:?key=products_list";

[is]
Application_MyApp[cached] = true
→ Use cached result

[else]
Application_MyApp[products] = "finder:?path=products/restore:";
Application_MyApp[] = "store:?key=products_list&value={{products}}&ttl=3600";
```

### 3. Lazy Loading

```ini
[render_user]
; Don't load related data unless needed
Application_MyApp_User[profile_id] = "{{profile_id}}";
Application_MyApp_User[] = "find:";

; Load related data only when accessed
[show_profile]
Application_MyApp_User[profile] = "finder:?path=profiles[id={{profile_id}}]/restore:";
```

### 4. Parallel Processing with Threading

```ini
[process_batch]
; Process 100 items in parallel instead of sequentially
[foreach]
Application_MyApp[items] = "thread:?callback=process:?item_id={{id}}&queue=true";

; Wait for all threads
Application_MyApp[results] = "pool:?threads={{pool}}";
```

---

## Security Best Practices

### 1. Input Validation

```php
// All parameters validated automatically
new Validation(
    $value,
    [
        new Validator\IsString,
        new Validator\Len\Equal(5),
        new Validator\IsEmail
    ]
);
```

### 2. SQL Injection Prevention

```ini
; Framework uses parameterized queries
Application_MyApp_User[id] = "123";
Application_MyApp_User[] = "find:";
→ Generates: SELECT * FROM users WHERE id = ?
→ Binds: [123]
```

### 3. XSS Prevention

```php
// Automatic escaping available
$this->sanitize($user_input);      // Remove HTML tags
$this->desanitize($stored_value);  // Restore after retrieval
```

### 4. CSRF Protection

Implement in controller:

```php
if (!$this->validateToken($this->request->csrf_token)) {
    throw new \InvalidArgumentException("Invalid CSRF token");
}
```

### 5. Sensitive Data Hiding

```ini
[hidden]
Application_MyApp[password] = 1;
Application_MyApp[api_key] = 1;
→ Won't show in debug output
```

---

## Contributing

### Reporting Bugs

1. Check existing issues
2. Create minimal reproduction
3. Include:
   - PHP version
   - Framework version
   - Error message
   - INI configuration
   - Expected vs actual behavior

### Submitting Code

1. Fork repository
2. Create feature branch
3. Follow code style
4. Write tests
5. Submit pull request

---

## License

MIT License - See LICENSE file for details.

---

## Resources

- **GitHub:** https://github.com/everdio/everdio2
- **Issues:** https://github.com/everdio/everdio2/issues
- **Discussions:** https://github.com/everdio/everdio2/discussions

---

## FAQ

**Q: Can I use Everdio2 in production?**
A: Yes. The framework is production-grade with comprehensive error handling, type validation, and signal management. Ensure your team understands the architecture.

**Q: Does it work on Windows?**
A: CLI features work. Threading requires Unix/Linux (POSIX signals).

**Q: What databases are supported?**
A: Any via PDO (MySQL, SQLite, PostgreSQL, etc.).

**Q: How do I deploy?**
A: Standard PHP deployment. Ensure PHP 8.0+, everdio executable in path, writable /tmp directory.

**Q: Performance compared to Laravel?**
A: Similar on throughput. Everdio2 may be 10-20% slower but with 50-70% less code.

**Q: Can I mix with other PHP code?**
A: Yes. Everdio2 is a framework, not a language. Use it where beneficial.

---

**Last Updated:** 2026-04-03
**Maintained By:** Everdio Contributors