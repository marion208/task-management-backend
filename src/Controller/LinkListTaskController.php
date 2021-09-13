<?php

namespace App\Controller;

use App\Entity\TasksList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LinkListTaskController extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TasksList::class);
    }

    public function getResultQueryListOfTasksLists()
    {
        $queryList = $this->getEntityManager()->createQuery(
            'SELECT l.id_list, l.name_list
            FROM App\Entity\TasksList l
            ORDER BY l.id_list'
        );
        $resultList = $queryList->getResult();

        $queryJoinTaskList = $this->getEntityManager()->createQuery(
            'SELECT l.id_list, l.name_list, t.id_task, t.name_task, t.id_status_task, s.id_status, s.name_status
            FROM App\Entity\TasksList l
            JOIN App\Entity\Task t
            JOIN App\Entity\ManageStatus s
            WHERE l.id_list = t.id_list_match AND t.id_status_task = s.id_status
            ORDER BY l.id_list, t.id_task'
        );

        $resultJoinTaskList = $queryJoinTaskList->getResult();

        $response = array();
        for ($i = 0; $i < count($resultList); $i++) {
            $response[$resultList[$i]['id_list']] = array(
                'id_list' => $resultList[$i]['id_list'],
                'name_list' => $resultList[$i]['name_list'],
                'nb_tasks_total' => 0,
                'nb_tasks_completed' => 0,
                'tasks' => array()
            );
        }
        foreach ($resultJoinTaskList as $oneTask) {
            $newArray = array(
                'id_task' => $oneTask['id_task'],
                'name_task' => $oneTask['name_task'],
                'id_status_task' => $oneTask['id_status'],
                'name_status_task' => $oneTask['name_status']
            );
            array_push($response[$oneTask['id_list']]['tasks'], $newArray);
            $response[$oneTask['id_list']]['nb_tasks_total']++;
            if ($oneTask['id_status'] == 1) {
                $response[$oneTask['id_list']]['nb_tasks_completed']++;
            }
        }

        return $response;
    }

    public function listsOfTasksByIdList($idList)
    {
        $queryTasksOfList = $this->getEntityManager()->createQuery(
            'SELECT l.id_list, t.id_status_task
            FROM App\Entity\TasksList l
            JOIN App\Entity\Task t
            WHERE l.id_list = t.id_list_match AND l.id_list = ' . $idList
        );

        $resultList = $queryTasksOfList->getResult();

        return $resultList;
    }
}
