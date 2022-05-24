<?php

namespace App\Storage;

use App\Model;

/**
 * @TODO так как предполагается что классы моделей будут унаследованы от
 *       абстрактного класса, то выполнение запросов стоит вынести туда, а в
 *       классах моделей стоит переопределить вызов методов родительского класса.
 *       А здесь останется только реализовать вызов методов инициализирующие
 *       запросы к БД.
 *       Данных подход позволит легче поддерживать и расширять возможности
 *       проектов (class Project) и задач (class Task)
 */
class DataStorage
{
    /**
     * @var \PDO 
     */
    public $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO('mysql:dbname=task_tracker;host=127.0.0.1', 'user');
    }

    /**
     * @param int $projectId
     * @throws Model\NotFoundException
     */
    public function getProjectById($projectId)
    {
        $stmt = $this->pdo->query('SELECT * FROM project WHERE id = ' . (int) $projectId);

        /**
         * @TODO присваивание значения лучше вынести из условия
         */
        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return new Model\Project($row);
        }

        throw new Model\NotFoundException();
    }

    /**
     * @param int $project_id
     * @param int $limit
     * @param int $offset
     */
    public function getTasksByProjectId(int $project_id, $limit, $offset)
    {
        /**
         * @TODO $limit и $offset можно передать в запрос метода query и тогда
         *       вызов execute не потребуется
         */
        $stmt = $this->pdo->query("SELECT * FROM task WHERE project_id = $project_id LIMIT ?, ?");
        $stmt->execute([$limit, $offset]);

        $tasks = [];
        foreach ($stmt->fetchAll() as $row) {
            $tasks[] = new Model\Task($row);
        }

        return $tasks;
    }

    /**
     * @param array $data
     * @param int $projectId
     * @return Model\Task
     */
    public function createTask(array $data, $projectId)
    {
        $data['project_id'] = $projectId;

        $fields = implode(',', array_keys($data));
        $values = implode(',', array_map(function ($v) {
            return is_string($v) ? '"' . $v . '"' : $v;
        }, $data));

        $this->pdo->query("INSERT INTO task ($fields) VALUES ($values)");
        $data['id'] = $this->pdo->query('SELECT MAX(id) FROM task')->fetchColumn();

        return new Model\Task($data);
    }
}
