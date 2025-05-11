<?php
class Contact
{
    private $jsonFile = __DIR__ . "/contact.json"; // Path to the JSON file

    // Read all contacts
    public function getAll()
    {
        if (!file_exists($this->jsonFile)) return [];

        $contents = file_get_contents($this->jsonFile);
        $decoded = json_decode($contents, true);

        return is_array($decoded) ? $decoded : [];
    }


    // Get a single contact by ID
    public function getById($id)
    {
        $contacts = $this->getAll();
        foreach ($contacts as $contact) {
            if ($contact["id"] == $id) return $contact;
        }
        return null;
    }

    // Add a new contact
    public function add($data)
    {
        if (!is_array($data) || empty($data)) return false;

        $contacts = $this->getAll();
        $data["id"] = count($contacts) > 0 ? max(array_column($contacts, "id")) + 1 : 1;
        $contacts[] = $data;
        return $this->save($contacts);
    }


    // Update an existing contact
    public function update($id, $newData)
    {
        if (!is_array($newData) || empty($newData)) return false;
        $contacts = $this->getAll();
        foreach ($contacts as &$contact) {
            if ($contact["id"] == $id) {
                $contact = array_merge($contact, $newData);
                return $this->save($contacts);
            }
        }
        return false;
    }


    // Delete an contact
    public function delete($id)
    {
        $contacts = array_filter($this->getAll(), fn($contact) => $contact["id"] != $id);
        return $this->save(array_values($contacts));
    }

    // Save data back to JSON
    private function save($data)
    {
        if (!is_array($data)) return false;
        return file_put_contents($this->jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }
}
