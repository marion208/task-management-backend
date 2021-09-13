<?php

namespace App\Controller;

use App\Entity\TasksList;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use App\Controller\LinkListTaskController;

class ListController extends AbstractController
{
    /**
     * @Route("/listsoftaskslists", methods="GET")
     */
    public function getListOfTasksLists(ManagerRegistry $registry): JsonResponse
    {
        $linkListTask = new LinkListTaskController($registry);
        $result = $linkListTask->getResultQueryListOfTasksLists();

        $response = new JsonResponse($result);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/addList", methods="POST")
     */
    public function addNewList(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $dataTask = json_decode($data, true);

        $name_list = $dataTask[0]['namelist'];

        $feedback = array();

        if (strlen(trim($name_list)) > 0 && strlen(trim($name_list)) <= 255) {
            $entityManager = $this->getDoctrine()->getManager();
            $list = new TasksList();
            $list->setNameList($name_list);
            $entityManager->persist($list);
            $entityManager->flush();

            $idlist = $list->getIdList();

            $response = new JsonResponse($idlist);
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');

            return $response;
        }

        return false;
    }

    /**
     * @Route("/updateNameList", methods="POST")
     */
    public function updateNameList(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $dataList = json_decode($data, true);

        $id_list = $dataList[0]['idlist'];
        $name_list = $dataList[0]['namelist'];

        $feedback = array();

        if (strlen(trim($name_list)) > 0 && strlen(trim($name_list)) <= 255) {
            $entityManager = $this->getDoctrine()->getManager();
            $list = $entityManager->getRepository(TasksList::class)->find($id_list);
            $list->setNameList($name_list);
            $entityManager->flush();

            $feedback = array(
                'status' => 'success',
                'description' => 'La liste a bien été mise à jour.'
            );
        }

        $tempoArray = array(
            'listname' => $name_list,
            'feedback' => $feedback
        );

        $response = new JsonResponse($tempoArray);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/deleteList/{idList}", methods="GET")
     */
    public function deleteList(int $idList): JsonResponse
    {
        $repository = $this->getDoctrine()->getRepository(TasksList::class);
        $list = $repository->find($idList);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($list);
        $entityManager->flush();

        $feedback = array(
            'status' => 'success',
            'description' => 'Liste supprimée.'
        );

        $response = new JsonResponse($feedback);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/updateProgressionList/{id}", methods="GET")
     */
    public function getUpdatedProgressionList(int $id, ManagerRegistry $registry): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);
        $idList = $task->getIdListMatch();

        $linkListTask = new LinkListTaskController($registry);
        $tasksOfList = $linkListTask->listsOfTasksByIdList($idList);

        $nbTasksTotal = 0;
        $nbTasksCompleted = 0;
        foreach ($tasksOfList as $oneTask) {
            $nbTasksTotal++;
            if ($oneTask['id_status_task'] == 1) {
                $nbTasksCompleted++;
            }
        }

        if ($nbTasksTotal > 0) {
            $progression = round($nbTasksCompleted * 100 / $nbTasksTotal, 0);
        } else {
            $progression = 0;
        }

        $feedback = array(
            'id_list' => $idList,
            'progression' => $progression
        );

        $response = new JsonResponse($feedback);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
