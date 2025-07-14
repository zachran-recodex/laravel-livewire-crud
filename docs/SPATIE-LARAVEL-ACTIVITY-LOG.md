# Laravel Activity Log Documentation

## Introduction

The `spatie/laravel-activitylog` package provides easy-to-use functions to log the activities of your application users. It can automatically log model events and store all activity in the `activity_log` table.

### Basic Example

```php
activity()->log('Look mum, I logged something');
```

You can retrieve all activity using the Activity model:

```php
Activity::all();
```

### Advanced Example

```php
activity()
   ->performedOn($anEloquentModel)
   ->causedBy($user)
   ->withProperties(['customProperty' => 'customValue'])
   ->log('Look mum, I logged something');

$lastLoggedActivity = Activity::all()->last();

$lastLoggedActivity->subject; // returns an instance of an eloquent model
$lastLoggedActivity->causer; // returns an instance of your user model
$lastLoggedActivity->getExtraProperty('customProperty'); // returns 'customValue'
$lastLoggedActivity->description; // returns 'Look mum, I logged something'
```

## Installation and Setup

### Install via Composer

```bash
composer require spatie/laravel-activitylog
```

The package will automatically register the service provider.

### Environment Configuration

If you want activities stored in a special database connection, define `ACTIVITY_LOGGER_DB_CONNECTION` in your `.env` file.

After configuration, clear the application config cache:

```bash
php artisan config:clear
```

### Publish and Run Migrations

Publish the migration:

```bash
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
```

Run the migrations:

```bash
php artisan migrate
```

### Publish Configuration (Optional)

```bash
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
```

### Configuration Options

```php
return [
    // If set to false, no activities will be saved to the database
    'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),

    // Clean command will delete activities older than this number of days
    'delete_records_older_than_days' => 365,

    // Default log name when none is specified
    'default_log_name' => 'default',

    // Auth driver for user models (null uses default Laravel auth driver)
    'default_auth_driver' => null,

    // If true, subject returns soft deleted models
    'subject_returns_soft_deleted_models' => false,

    // Activity model class
    'activity_model' => \Spatie\Activitylog\Models\Activity::class,

    // Database table name
    'table_name' => env('ACTIVITY_LOGGER_TABLE_NAME', 'activity_log'),

    // Database connection
    'database_connection' => env('ACTIVITY_LOGGER_DB_CONNECTION'),
];
```

## Basic Activity Logging

### Simple Logging

```php
activity()->log('Look mum, I logged something');

$lastActivity = Activity::all()->last();
$lastActivity->description; // returns 'Look mum, I logged something'
```

### Setting a Subject

Specify which object the activity is performed on:

```php
activity()
   ->performedOn($someContentModel)
   ->log('edited');

$lastActivity = Activity::all()->last();
$lastActivity->subject; // returns the model that was passed to performedOn
```

**Alias:** You can use `on()` instead of `performedOn()`

### Setting a Causer

Set who or what caused the activity:

```php
activity()
   ->causedBy($userModel)
   ->performedOn($someContentModel)
   ->log('edited');

$lastActivity = Activity::all()->last();
$lastActivity->causer; // returns the model that was passed to causedBy
```

**Aliases:**

- Use `by()` instead of `causedBy()`
- Use `causedByAnonymous()` or `byAnonymous()` for anonymous activities

**Note:** If you don't use `causedBy()`, the package automatically uses the logged-in user.

### Setting Custom Properties

Add custom properties to activities:

```php
activity()
   ->causedBy($userModel)
   ->performedOn($someContentModel)
   ->withProperties(['key' => 'value'])
   ->log('edited');

$lastActivity = Activity::all()->last();
$lastActivity->getExtraProperty('key'); // returns 'value'

// Query activities by custom properties
$activities = Activity::where('properties->key', 'value')->get();
```

### Setting Custom Created Date

```php
activity()
    ->causedBy($userModel)
    ->performedOn($someContentModel)
    ->createdAt(now()->subDays(10))
    ->log('created');
```

### Setting Custom Event

```php
activity()
    ->causedBy($userModel)
    ->performedOn($someContentModel)
    ->event('verified')
    ->log('The user has verified the content model.');
```

### Tap Activity Before Logging

Modify the activity before it's saved:

```php
use Spatie\Activitylog\Contracts\Activity;

activity()
   ->causedBy($userModel)
   ->performedOn($someContentModel)
   ->tap(function(Activity $activity) {
      $activity->my_custom_field = 'my special value';
   })
   ->log('edited');

$lastActivity = Activity::all()->last();
$lastActivity->my_custom_field; // returns 'my special value'
```

## Automatic Model Event Logging

### Basic Setup

Add the `LogsActivity` trait to your model:

```php
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class NewsItem extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'text'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'text']);
    }
}
```

### Configuration Options

#### Log All Fillable Attributes

```php
public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logFillable();
}
```

#### Log All Unguarded Attributes

```php
public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logUnguarded();
}
```

#### Log All Attributes

```php
public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logAll();
}
```

### Example: What Gets Logged

#### Creating a Model

```php
$newsItem = NewsItem::create([
   'name' => 'original name',
   'text' => 'Lorem ipsum'
]);

$activity = Activity::all()->last();
$activity->description; // returns 'created'
$activity->subject; // returns the NewsItem instance
$activity->changes; // returns ['attributes' => ['name' => 'original name', 'text' => 'Lorem ipsum']]
```

#### Updating a Model

```php
$newsItem->name = 'updated name';
$newsItem->save();

$activity = Activity::all()->last();
$activity->description; // returns 'updated'
$activity->changes; 
/* returns:
[
   'attributes' => [
        'name' => 'updated name',
        'text' => 'Lorem ipsum',
    ],
    'old' => [
        'name' => 'original name',
        'text' => 'Lorem ipsum',
    ],
]
*/
```

#### Deleting a Model

```php
$newsItem->delete();

$activity = Activity::all()->last();
$activity->description; // returns 'deleted'
$activity->changes; // returns ['attributes' => ['name' => 'updated name', 'text' => 'Lorem ipsum']]
```

## Advanced Logging Configuration

### Customizing Recorded Events

By default, the package logs `created`, `updated`, and `deleted` events. Customize this with the `$recordEvents` property:

```php
class NewsItem extends Model
{
    use LogsActivity;

    // Only the 'deleted' event will get logged automatically
    protected static $recordEvents = ['deleted'];
}
```

### Customizing Descriptions

```php
public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
}
```

### Customizing Log Names

```php
public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->useLogName('system');
}
```

### Ignoring Specific Attribute Changes

```php
public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logOnly(['name', 'text'])
        ->dontLogIfAttributesChangedOnly(['text']);
}
```

**Note:** By default, `updated_at` changes will trigger logging. Add it to `dontLogIfAttributesChangedOnly()` to prevent this.

### Logging Only Changed Attributes

```php
public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logOnly(['name', 'text'])
        ->logOnlyDirty();
}
```

### Logging Related Model Attributes

Use dot notation to log attributes of related models:

```php
public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logOnly(['name', 'text', 'user.name']);
}

public function user()
{
    return $this->belongsTo(User::class);
}
```

### Logging JSON Attribute Sub-keys

```php
class NewsItem extends Model
{
    use LogsActivity;

    protected $fillable = ['preferences', 'name'];
    protected $casts = ['preferences' => 'collection'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['preferences->notifications->status', 'preferences->hero_url']);
    }
}
```

### Preventing Empty Logs

```php
public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logOnly(['text'])
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
}
```

### Using CausesActivity Trait

Add to any model used as a causer to get an `actions` relationship:

```php
// In User model
use Spatie\Activitylog\Traits\CausesActivity;

// Retrieve all activities caused by the user
Auth::user()->actions;
```

### Runtime Logging Control

#### Disable Logging

```php
$newsItem->disableLogging();
$newsItem->update(['name' => 'This change will not be logged']);

// Or chain with update
$newsItem->disableLogging()->update(['name' => 'Not logged']);
```

#### Re-enable Logging

```php
$newsItem->enableLogging();
$newsItem->update(['name' => 'This change will be logged']);
```

### Tap Activity from Model Events

```php
use Spatie\Activitylog\Contracts\Activity;

class NewsItem extends Model
{
    use LogsActivity;

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->description = "activity.logs.message.{$eventName}";
    }
}
```

### Logging on Pivot Models

For pivot models with additional data:

```php
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Activitylog\Traits\LogsActivity;

final class PivotModel extends Pivot
{
    use LogsActivity;

    public $incrementing = true; // Required for pivot logging
}
```

**Note:** Add a primary key column `id` to your pivot table: `$table->id('id')`

## Pipeline System for Changes Manipulation

### Creating Custom Pipes

```php
use Spatie\Activitylog\Contracts\LoggablePipe;
use Spatie\Activitylog\EventLogBag;

class RemoveKeyFromLogChangesPipe implements LoggablePipe
{
    public function __construct(protected string $field) {}

    public function handle(EventLogBag $event, Closure $next): EventLogBag
    {
        Arr::forget($event->changes, ["attributes.{$this->field}", "old.{$this->field}"]);
        return $next($event);
    }
}
```

### Using Pipes

#### Runtime Application

```php
NewsItem::addLogChange(new RemoveKeyFromLogChangesPipe('name'));

$article = NewsItem::create(['name' => 'new article', 'text' => 'new article text']);
$article->update(['name' => 'updated article', 'text' => 'updated article text']);

Activity::all()->last()->changes();
/* Returns only:
[
    'attributes' => ['text' => 'updated text'],
    'old' => ['text' => 'original text']
]
*/
```

#### Model Boot Application

```php
class NewsItem extends Model
{
    use LogsActivity;

    protected static function booted(): void
    {
        static::addLogChange(new RemoveKeyFromLogChangesPipe('sensitive_field'));
    }
}
```

## Batch Logging

### Basic Batch Usage

Group multiple activities into a single batch:

```php
use Spatie\Activitylog\Facades\LogBatch;

LogBatch::startBatch();

$author = Author::create(['name' => 'Philip K. Dick']);
$book = Book::create(['name' => 'A Scanner Darkly', 'author_id' => $author->id]);
$book->update(['name' => 'A Scanner Darkly - Updated']);
$author->delete(); // This will cascade delete books

LogBatch::endBatch();
```

### Retrieving Batch Activities

```php
$batchUuid = LogBatch::getUuid(); // Save batch ID for later retrieval
LogBatch::endBatch();

$batchActivities = Activity::forBatch($batchUuid)->get();
```

### Batch Management

#### Check if Batch is Open

```php
if (LogBatch::isOpen()) {
    // Batch is currently open
}
```

#### Set Custom Batch UUID

For multi-job or multi-request scenarios:

```php
use Illuminate\Support\Str;

$uuid = Str::uuid();
LogBatch::setBatch($uuid);

// Use across multiple jobs/requests
```

#### Batch with Closure

```php
LogBatch::withinBatch(function(string $uuid) {
    activity()->log('Batch activity 1');
    $item = NewsItem::create(['name' => 'Batch item']);
    $item->update(['name' => 'Updated batch item']);
    $item->delete();
});
```

## Global Causer Resolution

### Setting Global Causer

Useful in jobs or contexts without logged-in users:

```php
use Spatie\Activitylog\Facades\CauserResolver;

$product = Product::first();
$causer = $product->owner;

CauserResolver::setCauser($causer);

$product->update(['name' => 'New name']);

Activity::all()->last()->causer; // Returns the set causer
```

### Causer Resolution with Callback

```php
CauserResolver::resolve(function() {
    return User::find(1); // Your causer resolution logic
});
```

## Using Placeholders

Replace placeholders in activity descriptions:

```php
activity()
    ->performedOn($article)
    ->causedBy($user)
    ->withProperties(['laravel' => 'awesome'])
    ->log('The subject name is :subject.name, the causer name is :causer.name and Laravel is :properties.laravel');

$lastActivity = Activity::all()->last();
$lastActivity->description; 
// Returns: 'The subject name is article name, the causer name is user name and Laravel is awesome'
```

Available placeholders:

- `:subject.{attribute}` - Subject model attributes
- `:causer.{attribute}` - Causer model attributes  
- `:properties.{key}` - Custom property values

## Multiple Log Management

### Default Log

Activities are logged to the 'default' log by default:

```php
activity()->log('Default log entry');

$lastActivity = Activity::all()->last();
$lastActivity->log_name; // returns 'default'
```

### Specifying Custom Logs

```php
activity('custom-log')->log('Custom log entry');

Activity::all()->last()->log_name; // returns 'custom-log'
```

### Model-Specific Log Names

```php
public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->useLogName('news_items_log');
}
```

### Retrieving from Specific Logs

```php
// Get activities from specific log
Activity::where('log_name', 'custom-log')->get();

// Using the inLog scope
Activity::inLog('custom-log')->get();

// Multiple logs
Activity::inLog('default', 'custom-log')->get();
Activity::inLog(['default', 'custom-log'])->get();
```

## Utility Functions

### Log Cleanup

Clean old activity records:

```bash
# Clean activities older than configured days
php artisan activitylog:clean

# Clean specific log
php artisan activitylog:clean my_log_channel

# Specify days to keep
php artisan activitylog:clean --days=7

# Force cleanup (for production)
php artisan activitylog:clean --force
```

#### Scheduled Cleanup

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('activitylog:clean')->daily();
}
```

#### MySQL Optimization

After cleanup, optimize the table:

```sql
OPTIMIZE TABLE activity_log;
-- OR
ANALYZE TABLE activity_log;
```

**Warning:** These operations lock the table - use only during maintenance.

### Global Logging Control

#### Disable All Logging

```php
activity()->disableLogging();
// All subsequent activities will not be logged
```

#### Enable Logging

```php
activity()->enableLogging();
```

#### Execute Without Logging

```php
activity()->withoutLogs(function () {
    // Code here won't generate any activity logs
    $user->update(['name' => 'New Name']);
    $post->delete();
});
```

## Best Practices

1. **Performance**: Use `logOnlyDirty()` to reduce unnecessary logs
2. **Storage**: Regularly clean old logs with the cleanup command
3. **Privacy**: Use `dontLogIfAttributesChangedOnly()` for sensitive fields
4. **Batching**: Group related activities using batch logging
5. **Custom Properties**: Use meaningful keys for custom properties
6. **Log Names**: Use descriptive log names for different activity types
7. **Pipelines**: Use pipes to sanitize or transform logged data
8. **Global Causers**: Set appropriate causers in background jobs

## Troubleshooting

### Common Issues

1. **Migration Fails**: Ensure database connection is properly configured
2. **No Activities Logged**: Check if logging is enabled in config
3. **Missing Causer**: Verify authentication or set causer manually
4. **Empty Changes**: Use `dontSubmitEmptyLogs()` to prevent empty logs
5. **Performance Issues**: Implement regular cleanup and consider log rotation

### Debug Mode

Enable detailed logging by setting `ACTIVITY_LOGGER_ENABLED=true` in your `.env` file and check the `activity_log` table structure matches your expectations.
