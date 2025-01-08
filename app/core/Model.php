<?php
//defined('ROOTPATH') OR exit('Access Denied!');

Trait Model
{
    use Database;

    public $errors = [];

    public function findAll($limit = 10, $offset = 0, $order_column = "id", $order_type = "ASC")
    {
        $query = "select * from $this->table order by $order_column $order_type limit $limit offset $offset";
        return $this->query($query);
    }

    public function where($data, $data_not = [], $limit = 10, $offset = 0, $order_column = "id", $order_type = "ASC")
    {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "select * from $this->table where ";

        foreach ($keys as $key) {
            $query .= $key . " = :". $key . " && ";
        }

        foreach ($keys_not as $key) {
            $query .= $key . " != :". $key . " && ";
        }

        $query = trim($query," && ");

        $query .= " order by $order_column $order_type limit $limit offset $offset";
        $data = array_merge($data, $data_not);

        return $this->query($query, $data);
    }

    public function first($data, $data_not = [], $limit = 10, $offset = 0)
    {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "select * from $this->table where ";

        foreach ($keys as $key) {
            $query .= $key . " = :". $key . " && ";
        }

        foreach ($keys_not as $key) {
            $query .= $key . " != :". $key . " && ";
        }

        $query = trim($query," && ");

        $query .= " limit $limit offset $offset";
        $data = array_merge($data, $data_not);

        $result = $this->query($query, $data);
        if($result)
            return $result[0];

        return false;
    }

    public function insert($data)
    {
        if(!empty($this->allowedColumns))
        {
            foreach ($data as $key => $value) {
                if(!in_array($key, $this->allowedColumns))
                {
                    unset($data[$key]);
                }
            }
        }

        $keys = array_keys($data);

        $query = "insert into $this->table (".implode(",", $keys).") values (:".implode(",:", $keys).")";
        $this->query($query, $data);

        return false;
    }

    public function update($id, $data, $id_column = 'id')
    {
        if(!empty($this->allowedColumns))
        {
            foreach ($data as $key => $value) {
                if(!in_array($key, $this->allowedColumns))
                {
                    unset($data[$key]);
                }
            }
        }

        $keys = array_keys($data);
        $query = "update $this->table set ";

        foreach ($keys as $key) {
            $query .= $key . " = :". $key . ", ";
        }

        $query = trim($query,", ");

        $query .= " where $id_column = :$id_column ";

        $data[$id_column] = $id;

        $this->query($query, $data);
        return false;
    }

    public function delete($id, $id_column = 'id')
    {
        $data[$id_column] = $id;
        $query = "delete from $this->table where $id_column = :$id_column ";
        $this->query($query, $data);

        return false;
    }

	public function getTotalCount($conditions = [], $conditions_not = [])
{
    $query = "SELECT COUNT(*) as total FROM $this->table";

    if (!empty($conditions) || !empty($conditions_not)) {
        $query .= " WHERE ";

        foreach ($conditions as $key => $value) {
            $query .= "$key = :$key AND ";
        }

        foreach ($conditions_not as $key => $value) {
            $query .= "$key != :$key AND ";
        }

        $query = rtrim($query, " AND ");
    }

    $data = array_merge($conditions, $conditions_not);

    $result = $this->query($query, $data);

    return $result[0]->total;
}

    public function search($searchFields = [], $exactMatchFields = [], $limit = 10, $offset = 0, $order_column = "id", $order_type = "ASC")
    {
        $query = "SELECT * FROM $this->table WHERE 1=1 ";
        $data = [];

        foreach ($searchFields as $key => $value) {
            if ($value !== null && $value !== '') {
                if (in_array($key, $exactMatchFields)) {
                    $query .= " AND $key = :$key ";
                    $data[$key] = $value;
                } else {
                    $query .= " AND $key LIKE :$key ";
                    $data[$key] = '%' . $value . '%';
                }
            }
        }

        $query .= " ORDER BY $order_column $order_type LIMIT $limit OFFSET $offset";

        return $this->query($query, $data);
    }

    public function join($tables, $joinConditions, $options = [])
{
    $defaults = [
        'type' => 'LEFT',
        'limit' => 10,
        'offset' => 0,
        'order_column' => 'id',
        'order_type' => 'ASC',
        'where' => [],
        'where_not' => [],
        'search' => [],
        'exact_match' => [],
        'params' => []
    ];
    
    $options = array_merge($defaults, $options);
    
    $query = "SELECT ";
    
    if (!empty($this->allowedColumns)) {
        $currentTableColumns = array_map(function($col) {
            return "$this->table.$col";
        }, $this->allowedColumns);
    } else {
        $currentTableColumns = ["$this->table.*"];
    }
    
    foreach ($tables as $tableName => $columns) {
        if (!empty($columns) && is_array($columns)) {
            foreach ($columns as $column) {
                $currentTableColumns[] = "$tableName.$column as {$tableName}_{$column}";
            }
        } else {
            $currentTableColumns[] = "$tableName.*";
        }
    }
    
    $query .= implode(', ', $currentTableColumns);
    $query .= " FROM $this->table";
    
    foreach ($tables as $tableName => $columns) {
        if (isset($joinConditions[$tableName])) {
            $query .= " " . $options['type'] . " JOIN $tableName ON {$joinConditions[$tableName]}";
        }
    }
    
    $whereConditions = [];
    $queryParams = [];
    
    if (!empty($options['where'])) {
        foreach ($options['where'] as $key => $value) {
            $paramKey = str_replace('.', '_', $key); // Convert dots to underscores for parameter names
            $whereConditions[] = "$key = :where_$paramKey";
            $queryParams["where_$paramKey"] = $value;
        }
    }
    
    if (!empty($options['where_not'])) {
        foreach ($options['where_not'] as $key => $value) {
            $paramKey = str_replace('.', '_', $key);
            $whereConditions[] = "$key != :not_$paramKey";
            $queryParams["not_$paramKey"] = $value;
        }
    }
    
    if (!empty($options['search'])) {
        foreach ($options['search'] as $key => $value) {
            if ($value !== null && $value !== '') {
                $paramKey = str_replace('.', '_', $key);
                if (!empty($options['exact_match']) && in_array($key, $options['exact_match'])) {
                    $whereConditions[] = "$key = :search_$paramKey";
                    $queryParams["search_$paramKey"] = $value;
                } else {
                    $whereConditions[] = "$key LIKE :search_$paramKey";
                    $queryParams["search_$paramKey"] = '%' . $value . '%';
                }
            }
        }
    }
    
    if (!empty($whereConditions)) {
        $query .= " WHERE " . implode(' AND ', $whereConditions);
    }
    
    $query .= " ORDER BY {$options['order_column']} {$options['order_type']}";
    
    if ($options['limit'] > 0) {
        $query .= " LIMIT {$options['limit']} OFFSET {$options['offset']}";
    }
    
    $queryParams = array_merge($queryParams, $options['params']);
    
    return $this->query($query, $queryParams);
}

public function getJoinTotalCount($tables, $joinConditions, $options = [])
{
    $joinType = isset($options['type']) ? $options['type'] : 'LEFT';
    
    $query = "SELECT COUNT(*) as total FROM $this->table";
    
    foreach ($tables as $tableName => $columns) {
        if (isset($joinConditions[$tableName])) {
            $query .= " $joinType JOIN $tableName ON {$joinConditions[$tableName]}";
        }
    }
    
    $whereConditions = [];
    $queryParams = [];
    
    if (!empty($options['where'])) {
        foreach ($options['where'] as $key => $value) {
            $paramKey = str_replace('.', '_', $key);
            $whereConditions[] = "$key = :where_$paramKey";
            $queryParams["where_$paramKey"] = $value;
        }
    }
    
    if (!empty($options['search'])) {
        foreach ($options['search'] as $key => $value) {
            if ($value !== null && $value !== '') {
                $paramKey = str_replace('.', '_', $key);
                if (!empty($options['exact_match']) && in_array($key, $options['exact_match'])) {
                    $whereConditions[] = "$key = :search_$paramKey";
                    $queryParams["search_$paramKey"] = $value;
                } else {
                    $whereConditions[] = "$key LIKE :search_$paramKey";
                    $queryParams["search_$paramKey"] = '%' . $value . '%';
                }
            }
        }
    }
    
    if (!empty($whereConditions)) {
        $query .= " WHERE " . implode(' AND ', $whereConditions);
    }
    
    $result = $this->query($query, $queryParams);
    return isset($result[0]->total) ? $result[0]->total : 0;
}

}