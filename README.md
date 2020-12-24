## Installation

Install via composer.

```bash
$ composer require qubus/event-dispatcher
```

## Usage

### Create an event dispatcher

```php
$dispatcher = new Qubus\EventDispatcher\Dispatcher();
```

### Add a listener for the specified event

There are two types of listeners: `callable` and `EventListener` 
 
- `Qubus\EventDispatcher\EventListener` 

```php
use Qubus\EventDispatcher\Event;
use Qubus\EventDispatcher\EventListener;

class FooListener implements EventListener
{
     public function handle(Event $event)
     {
         //do something
     }
}

$dispatcher->addListener('kernel.event', new FooListener());
```

- `callable`

```php
$dispatcher->addListener('kernel.event', function(Event $event){
    //do something
});
```

### Add a subscriber

```php
use Qubus\EventDispatcher\Event;
use Qubus\EventDispatcher\EventSubscriber;

class FooSubscriber implements EventSubscriber
{
     public static function getSubscribedEvents()
     {
        return [
            'kernel.event' => 'onFoo',
            'bar.event' => 'onBar'
        ];
     }
     
    public function onFoo(Event $event)
    {
      //do something
    }
    
    public function onBar()
    {
       //do something
    }
}

$dispatcher->addSubscriber(new FooSubscriber());
```

### Dispatches the event to the registered listeners

Just provides the event name.

```php
$dispatcher->dispatch('kernel.event');
```

You can also dispatch with an event instance.

```php
$dispatcher->dispatch(new GenericEvent('kernel.event'));
```

Or dispatch with the event name using a constant `EVENT_NAME` which is located in the `Event` interface:

```php
$dispatcher->dispatch(GenericEvent::EVENT_NAME);
```

### Propagation

You can call `stopPropagation` to stop event propagation on the event instance.

```php
$dispatcher->addListener('kernel.event', function(Event $event){
    $event->stopPropagation();
});

$emitter->addListener('kernel.event', function ($event) {
    // This will not be triggered
});

$dispatcher->dispatch('kernel.event');
```

Checks whether propagation is stopped
 
 ```php
 $event = new GenericEvent('kernel.event');
 $dispatcher->dispatch($event);
 
 $event->isPropagationStopped();
 ```

 ## Hooks
 This libary also includes a simple action and filter hook system.

### Usage Action
 ```php
use Qubus\EventDispatcher\ActionFilter\Observer;

(new Observer)->action->addAction("header", function() {
	echo "Hello!";
});

(new Observer)->action->doAction("header");
```

### Usage Filter
```php
use Qubus\EventDispatcher\ActionFilter\Observer;

(new Observer)->filter->addFilter("header", function($value) {
	return $value . " World!";
});

echo (new Observer)->filter->applyFilter("header", "Hello");
```
 
## License
Released under the MIT [License](https://opensource.org/licenses/MIT).