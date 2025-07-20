<?php

/**
 * Qubus\EventDispatcher
 *
 * @link       https://github.com/QubusPHP/event-dispatcher
 * @copyright  2020 Joshua Parker <joshua@joshuaparker.dev>
 * @copyright  2018 Filip Štamcar (original author Tor Morten Jensen)
 * @license    https://opensource.org/licenses/mit-license.php MIT License
 */

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
     * @var ?object $subject
     */
    protected ?object $subject;

    /**
     * Array of arguments.
     *
     * @var array $arguments
     */
    protected array $arguments = [];

    /**
     * @param string $name
     * @param object|null $subject
     * @param array $arguments
     */
    public function __construct(string $name = '', ?object $subject = null, array $arguments = [])
    {
        $this->name = $name;
        $this->subject = $subject;
        $this->arguments = $arguments;
    }

    /**
     * {@inheritDoc}
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
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the subject.
     *
     * @param object|null $subject
     * @return $this
     */
    public function setSubject(?object $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Gets the subject.
     *
     * @return null|object
     */
    public function getSubject(): ?object
    {
        return $this->subject;
    }

    /**
     * Sets a argument to the event.
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setArgument(string $name, mixed $value): static
    {
        $this->arguments[$name] = $value;

        return $this;
    }

    /**
     * Gets the argument by its key.
     *
     * @param string $name
     * @return mixed
     * @throws TypeException
     */
    public function getArgument(string $name): mixed
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
    public function setArguments(array $arguments): static
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
