<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/addTask", methods="POST")
     */
    public function addNewTask(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $dataTask = json_decode($data, true);

        $id_list = $dataTask[0]['idlist'];
        $name_task = $dataTask[0]['nametask'];

        $feedback = array();

        if (strlen(trim($name_task)) > 0 && strlen(trim($name_task)) <= 255) {
            $entityManager = $this->getDoctrine()->getManager();
            $task = new Task();
            $task->setNameTask($name_task);
            $task->setIdStatusTask(0);
            $task->setIdListMatch((int)$id_list);
            $entityManager->persist($task);
            $entityManager->flush();

            $idTask = $task->getIdTask();

            $feedback = array(
                'status' => 'success',
                'description' => 'La tâche a bien été ajoutée.'
            );
        }

        $tempoArray = array(
            'idtask' => $idTask,
            'taskname' => $name_task,
            'feedback' => $feedback
        );

        $response = new JsonResponse($tempoArray);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/updateNameTask", methods="POST")
     */
    public function updateNameTask(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $dataTask = json_decode($data, true);

        $id_task = $dataTask[0]['idtask'];
        $name_task = $dataTask[0]['nametask'];

        $feedback = array();

        if (strlen(trim($name_task)) > 0 && strlen(trim($name_task)) <= 255) {
            $entityManager = $this->getDoctrine()->getManager();
            $task = $entityManager->getRepository(Task::class)->find($id_task);
            $task->setNameTask($name_task);
            $entityManager->flush();

            $feedback = array(
                'status' => 'success',
                'description' => 'La tâche a bien été mise à jour.'
            );
        }

        $tempoArray = array(
            'taskname' => $name_task,
            'feedback' => $feedback
        );

        $response = new JsonResponse($tempoArray);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/updateStatusTask", methods="POST")
     */
    public function updateStatusTask(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $dataTask = json_decode($data, true);

        $id_task = $dataTask[0]['idtask'];
        $status_task = $dataTask[0]['statustask'];

        $feedback = array();

        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id_task);
        $task->setIdStatusTask($status_task);
        $entityManager->flush();

        $feedback = array(
            'status' => $status_task
        );

        $response = new JsonResponse($feedback);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/deleteTask/{idTask}", methods="GET")
     */
    public function deleteTask(int $idTask): JsonResponse
    {
        $repository = $this->getDoctrine()->getRepository(Task::class);
        $task = $repository->find($idTask);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($task);
        $entityManager->flush();

        $feedback = array(
            'status' => 'success',
            'description' => 'Tâche supprimée.'
        );

        $response = new JsonResponse($feedback);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
