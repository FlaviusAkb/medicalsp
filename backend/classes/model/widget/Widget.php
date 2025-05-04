<?php
class Widget
{
    private $jsonFile = __DIR__ . "/widget.json"; // Path to the JSON file

    // Read all widgets
    public function getAll()
    {
        if (!file_exists($this->jsonFile)) return [];

        $contents = file_get_contents($this->jsonFile);
        $decoded = json_decode($contents, true);

        return is_array($decoded) ? $decoded : [];
    }


    // Get a single widget by ID
    public function getById($id)
    {
        $widgets = $this->getAll();
        foreach ($widgets as $widget) {
            if ($widget["id"] == $id) return $widget;
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

    // Add a new widget
    public function add($data)
    {
        if (!is_array($data) || empty($data)) return false;

        $widgets = $this->getAll();
        $data["id"] = count($widgets) > 0 ? max(array_column($widgets, "id")) + 1 : 1;
        $widgets[] = $data;
        return $this->save($widgets);
    }


    // Update an existing widget
    public function update($id, $newData)
    {
        if (!is_array($newData) || empty($newData)) return false;
        $widgets = $this->getAll();
        foreach ($widgets as &$widget) {
            if ($widget["id"] == $id) {
                $widget = array_merge($widget, $newData);
                return $this->save($widgets);
            }
        }
        return false;
    }


    // Delete an widget
    public function delete($id)
    {
        $widgets = array_filter($this->getAll(), fn($widget) => $widget["id"] != $id);
        return $this->save(array_values($widgets));
    }

    // Save data back to JSON
    private function save($data)
    {
        if (!is_array($data)) return false;
        return file_put_contents($this->jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }
}
