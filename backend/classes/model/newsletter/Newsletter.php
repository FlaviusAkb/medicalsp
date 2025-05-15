<?php
class Newsletter
{
    private $jsonFile = __DIR__ . "/newsletter.json"; // Path to the JSON file
    public $startingYear = 2022;

    // Read all newsletters
    public function getAll()
    {
        if (!file_exists($this->jsonFile)) return [];
        return json_decode(file_get_contents($this->jsonFile), true);
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
    public function getByUrl($path)
    {
        $newsletters = $this->getAll();
        foreach ($newsletters as $newsletter) {
            if (strtolower($newsletter["pdf_url"]) == strtolower($path)) return $newsletter;
        }
        return null;
    }

    // Add a new newsletter
    public function add($data)
    {
        $newsletters = $this->getAll();
        $data["id"] = count($newsletters) > 0 ? max(array_column($newsletters, "id")) + 1 : 1;
        $newsletters[] = $data;
        return $this->save($newsletters);
    }

    // Update an existing newsletter
    public function update($id, $newData)
    {
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
        return file_put_contents($this->jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    // Save data back to JSON
    public function getVolumeList($source = false)
    {
        $startingYear = $this->startingYear;
        if ($source) {
            $newsletters = $source;
        } else {
            $newsletters = $this->getAll();
        }

        $list = [];
        foreach ($newsletters as $key => $value) {
            if (!array_key_exists($value["volume"], $list)) {
                $list[$value["volume"]] = [];
            }
            array_push($list[$value["volume"]], $value["issue"]);
        }
        ksort($list);
        $finalArray = [];
        $startArray = array_key_first($list);
        foreach ($list as $key => $volume) {
            if ($startArray != $key) {
                $startArray = $key;
                $startingYear++;
            }
            // first sort the issues of volume
            $unique = array_unique($volume); // 1) remove duplicates
            sort($unique); // 2) sort in-place (re-indexes, ascending)

            if (!array_key_exists($startingYear, $finalArray)) {
                $finalArray[$startingYear] = [];
            }

            foreach ($unique as $key2 => $issue) {
                array_push($finalArray[$startingYear], ["email" => "Issue $issue, $startingYear", "link" => "issue$issue-$startingYear"]);
            }
        }

        return $finalArray;
    }



    public function getSelection($year, $issue, $source = false)
    {
        $volume = $year - $this->startingYear + 1;
        if ($source) {
            $newsletters = $source;
        } else {
            $newsletters = $this->getAll();
        }

        $finalSelection = [];
        foreach ($newsletters as $key => $newsletter) {
            if ($newsletter["volume"] == $volume && $newsletter["issue"] == $issue)
                array_push($finalSelection, $newsletter);
        }
        usort($finalSelection, function ($a, $b) {
            $timeA = strtotime($a['publish_date']);
            $timeB = strtotime($b['publish_date']);
            return $timeA <=> $timeB;  // for descending swap $timeB <=> $timeA
        });

        return $finalSelection;
    }
}
