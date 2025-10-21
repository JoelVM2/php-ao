<?php
class Task {

    private int $id;
    private string $title;
    private string $description;
    private string $dueDate;
    private bool $completed;

    public function __construct(int $id, string $title, string $description, string $dueDate, bool $completed = false) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->dueDate = $dueDate;
        $this->completed = $completed;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getDueDate(): string {
        return $this->dueDate;
    }

    public function isCompleted(): bool {
        return $this->completed;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setDueDate(string $dueDate): void {
        $this->dueDate = $dueDate;
    }

    public function setCompleted(bool $completed): void {
        $this->completed = $completed;
    }
}
?>
