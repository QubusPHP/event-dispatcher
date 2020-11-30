<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher;

use Qubus\Exception\Data\TypeException;

use function array_key_exists;
use function sprintf;

class GenericEvent extends BaseEvent implements Event
{
    /**
     * The event name.
     */
    protected string $name;

    /**
     * The subject.
     *
     * @var object $subject
     */
    protected $subject;

    /**
     * Array of arguments.
     *
     * @var array $arguments
     */
    protected array $arguments = [];

    /**
     * @param object $subject
     * @param array $arguments
     */
    public function __construct(string $name = '', $subject = null, array $arguments = [])
    {
        $this->name = $name;
        $this->subject = $subject;
        $this->arguments = $arguments;
    }

    /**
     * Gets the event name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the event name.
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the subject.
     *
     * @param object $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Gets the subject.
     *
     * @return null|object
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets a argument to the event.
     *
     * @param mixed  $value
     * @return $this
     */
    public function setArgument(string $name, $value)
    {
        $this->arguments[$name] = $value;

        return $this;
    }

    /**
     * Gets the argument by its key.
     *
     * @return mixed
     * @throws TypeException
     */
    public function getArgument(string $name)
    {
        if ($this->hasArgument($name)) {
            return $this->arguments[$name];
        }

        throw new TypeException(sprintf('Argument "%s" not found.', $name));
    }

    /**
     * Sets array of arguments.
     *
     * @param array $arguments
     * @return $this
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Gets all arguments.
     *
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Has argument.
     */
    public function hasArgument(string $key): bool
    {
        return array_key_exists($key, $this->arguments);
    }
}
