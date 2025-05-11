<?php
class Newsletter
{
    private $jsonFile = __DIR__ . "/newsletter.json"; // Path to the JSON file

    // Read all newsletters
    public function getAll()
    {
        if (!file_exists($this->jsonFile)) return [];

        $contents = file_get_contents($this->jsonFile);
        $decoded = json_decode($contents, true);

        return is_array($decoded) ? $decoded : [];
    }


    // Get a single newsletter by ID
    public function getById($id)
    {
        $newsletters = $this->getAll();
        foreach ($newsletters as $newsletter) {
            if ($newsletter["id"] == $id) return $newsletter;
        }
        return null;
    }

    // Add a new newsletter
    public function add($data)
    {
        if (!is_array($data) || empty($data)) return false;

        $newsletters = $this->getAll();
        $data["id"] = count($newsletters) > 0 ? max(array_column($newsletters, "id")) + 1 : 1;
        $newsletters[] = $data;
        return $this->save($newsletters);
    }


    // Update an existing newsletter
    public function update($id, $newData)
    {
        if (!is_array($newData) || empty($newData)) return false;
        $newsletters = $this->getAll();
        foreach ($newsletters as &$newsletter) {
            if ($newsletter["id"] == $id) {
                $newsletter = array_merge($newsletter, $newData);
                return $this->save($newsletters);
            }
        }
        return false;
    }


    // Delete an newsletter
    public function delete($id)
    {
        $newsletters = array_filter($this->getAll(), fn($newsletter) => $newsletter["id"] != $id);
        return $this->save(array_values($newsletters));
    }

    // Save data back to JSON
    private function save($data)
    {
        if (!is_array($data)) return false;
        return file_put_contents($this->jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }
}
