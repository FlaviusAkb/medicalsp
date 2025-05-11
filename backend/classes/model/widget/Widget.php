<?php
class Widget
{
    private $jsonFile = __DIR__ . "/widget.json";

    // Read all widgets
    public function getAll()
    {
        if (!file_exists($this->jsonFile)) return [];

        $contents = file_get_contents($this->jsonFile);
        $decoded = json_decode($contents, true);

        return is_array($decoded) ? $decoded : [];
    }

    // Get a single widget by UUID
    public function getById($id)
    {
        $widgets = $this->getAll();
        foreach ($widgets as $widget) {
            if ($widget["id"] === $id) return $widget;
        }
        return null;
    }

    public function getByUrl($path)
    {
        $widgets = $this->getAll();
        foreach ($widgets as $widget) {
            if (strtolower($widget["image_url"]) == strtolower($path)) return $widget;
        }
        return null;
    }

    // Add a new widget with UUID
    public function add($data)
    {
        if (!is_array($data) || empty($data)) return false;

        $widgets = $this->getAll();

        // Generate UUID if missing
        $data["id"] = $data["id"] ?? $this->generateUuid();

        $widgets[] = $data;
        return $this->save($widgets);
    }

    // Update an existing widget by UUID
    public function update($id, $newData)
    {
        if (!is_array($newData) || empty($newData)) return false;

        $widgets = $this->getAll();
        foreach ($widgets as &$widget) {
            if ($widget["id"] === $id) {
                $widget = array_merge($widget, $newData);
                return $this->save($widgets);
            }
        }
        return false;
    }

    // Delete a widget by UUID
    public function delete($id)
    {
        $widgets = array_filter($this->getAll(), fn($widget) => $widget["id"] !== $id);
        return $this->save(array_values($widgets));
    }

    // Save data back to JSON
    private function save($data)
    {
        if (!is_array($data)) return false;
        return file_put_contents($this->jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function saveAll($widgets)
    {
        file_put_contents($this->jsonFile, json_encode($widgets, JSON_PRETTY_PRINT));
    }

    // Generate UUID (v4)
    private function generateUuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),     // 32 bits
            mt_rand(0, 0xffff),                         // 16 bits
            mt_rand(0, 0x0fff) | 0x4000,                // Version 4
            mt_rand(0, 0x3fff) | 0x8000,                // Variant 10x
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff) // 48 bits
        );
    }

    // Recalculate positions of widgets
    public function recalculatePositions()
    {
        $widgets = $this->getAll();

        // Recalculate positions
        foreach ($widgets as $index => &$widget) {
            $widget['position'] = $index;
        }

        // Save the updated positions
        $this->saveAll($widgets);
    }
}
