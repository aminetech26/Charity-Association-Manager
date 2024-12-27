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

	public function getTotalCount()
	{
		$query = "SELECT COUNT(*) as total FROM $this->table";
		$result = $this->query($query);
		return $result[0]->total;
	}

}