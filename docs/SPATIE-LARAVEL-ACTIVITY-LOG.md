Introduction
The spatie/laravel-activitylog package provides easy to use functions to log the activities of the users of your app. It can also automatically log model events. All activity will be stored in the activity_log table.

Here's a litte demo of how you can use it:

activity()->log('Look mum, I logged something');
You can retrieve all activity using the Spatie\Activitylog\Models\Activity model.

Activity::all();
Here's a more advanced example:

activity()
->performedOn($anEloquentModel)
->causedBy($user)
->withProperties(['customProperty' => 'customValue'])
->log('Look mum, I logged something');

$lastLoggedActivity = Activity::all()->last();

$lastLoggedActivity->subject; //returns an instance of an eloquent model
$lastLoggedActivity->causer; //returns an instance of your user model
$lastLoggedActivity->getExtraProperty('customProperty'); //returns 'customValue'
$lastLoggedActivity->description; //returns 'Look mum, I logged something'
Here's an example on event logging.

$newsItem->name = 'updated name';
$newsItem->save();

//updating the newsItem will cause an activity being logged
$activity = Activity::all()->last();

$activity->description; //returns 'updated'
$activity->subject; //returns the instance of NewsItem that was created
Calling $activity->changes will return this array:

[
'old' => [
'name' => 'original name',
'text' => 'Lorum',
],
'attributes' => [
'name' => 'updated name',
'text' => 'Lorum',
],
];

Installation and Setup
The package can be installed via composer:

composer require spatie/laravel-activitylog
The package will automatically register the service provider.

If you want your activities to be stored in a special database connection you can define ACTIVITY_LOGGER_DB_CONNECTION in your .env file.

After you've configured everything you should clear the application config cache via artisan config:clear.

You can publish the migration with:

php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
After the migration has been published you can create the activity_log table by running the migrations:

php artisan migrate
You can optionally publish the config file with:

php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
This is the contents of the published config file:

return [

    /*
     * If set to false, no activities will be saved to the database.
     */
    'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),

    /*
     * When the clean-command is executed, all recording activities older than
     * the number of days specified here will be deleted.
     */
    'delete_records_older_than_days' => 365,

    /*
     * If no log name is passed to the activity() helper
     * we use this default log name.
     */
    'default_log_name' => 'default',

    /*
     * You can specify an auth driver here that gets user models.
     * If this is null we'll use the current Laravel auth driver.
     */
    'default_auth_driver' => null,

    /*
     * If set to true, the subject returns soft deleted models.
     */
    'subject_returns_soft_deleted_models' => false,

    /*
     * This model will be used to log activity.
     * It should implement the Spatie\Activitylog\Contracts\Activity interface
     * and extend Illuminate\Database\Eloquent\Model.
     */
    'activity_model' => \Spatie\Activitylog\Models\Activity::class,

    /*
     * This is the name of the table that will be created by the migration and
     * used by the Activity model shipped with this package.
     */
    'table_name' => env('ACTIVITY_LOGGER_TABLE_NAME', 'activity_log'),

    /*
     * This is the database connection that will be used by the migration and
     * the Activity model shipped with this package. In case it's not set
     * Laravel's database.default will be used instead.
     */
    'database_connection' => env('ACTIVITY_LOGGER_DB_CONNECTION'),
];

Logging activity
##Description
This is the most basic way to log activity:

activity()->log('Look mum, I logged something');
You can retrieve the activity using the Spatie\Activitylog\Models\Activity model.

$lastActivity = Activity::all()->last(); //returns the last logged activity

$lastActivity->description; //returns 'Look mum, I logged something';
##Setting a subject
You can specify on which object the activity is performed by using performedOn():

activity()
->performedOn($someContentModel)
->log('edited');

$lastActivity = Activity::all()->last(); //returns the last logged activity

$lastActivity->subject; //returns the model that was passed to `performedOn`;
The performedOn()-function has a shorter alias name: on()

##Setting a causer
You can set who or what caused the activity by using causedBy():

activity()
->causedBy($userModel)
->performedOn($someContentModel)
->log('edited');

$lastActivity = Activity::all()->last(); //returns the last logged activity

$lastActivity->causer; //returns the model that was passed to `causedBy`;
The causedBy()-function has a shorter alias named: by()

If you're not using causedBy() the package will automatically use the logged in user.

If you don't want to associate a model as causer of activity, you can use causedByAnonymous() (or the shorter alias: byAnonymous()).

##Setting custom properties
You can add any property you want to an activity by using withProperties()

activity()
->causedBy($userModel)
->performedOn($someContentModel)
->withProperties(['key' => 'value'])
->log('edited');

$lastActivity = Activity::all()->last(); //returns the last logged activity

$lastActivity->getExtraProperty('key'); //returns 'value'

$lastActivity->where('properties->key', 'value')->get(); // get all activity where the `key` custom property is 'value'
##Setting custom created date
You can set a custom activity created_at date time by using createdAt()

activity()
->causedBy($userModel)
->performedOn($someContentModel)
->createdAt(now()->subDays(10))
->log('created');
##Setting custom event
You can set a custom activity event by using event()

activity()
->causedBy($userModel)
->performedOn($someContentModel)
->event('verified')
->log('The user has verified the content model.');
##Tap Activity before logged
You can use the tap() method to fill properties and add custom fields before the activity is saved.

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

Cleaning up the log
After using the package for a while you might have recorded a lot of activity. This package provides an artisan command activitylog:clean to clean the log.

Running this command will result in the deletion of all recorded activity that is older than the number of days specified in the delete_records_older_than_days of the config file.

You can leverage Laravel's scheduler to run the clean up command now and then.

php artisan activitylog:clean
//app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
$schedule->command('activitylog:clean')->daily();
}
If you want to automatically cleanup your production system you should append the --force option as the command will otherwise ask you to confirm the action. This is to prevent accidental data loss.

##Define the log to clean
If you want to clean just one log you can define it as command argument. It will filter the log_name attribute of the Activity model.

php artisan activitylog:clean my_log_channel
##Overwrite the days to keep per call
You can define the days to keep for each call as command option. This will overwrite the config for this run.

php artisan activitylog:clean --days=7
##MySQL - Rebuild index & get back space after clean.
After clean, you might experience database table size still allocated more than actual lines in table, execute this line in MySQL to OPTIMIZE / ANALYZE table.

OPTIMIZE TABLE activity_log;
OR

ANALYZE TABLE activity_log;
*this SQL operation will lock write/read of database, use ONLY when server under maintanance mode.
