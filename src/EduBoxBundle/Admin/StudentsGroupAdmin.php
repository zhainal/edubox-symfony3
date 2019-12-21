<?php


namespace EduBoxBundle\Admin;


use EduBoxBundle\Event\AdminEvent;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class StudentsGroupAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'group';
    protected $baseRouteName = 'edubox.admin.students_group';
    protected $studentClassRepository;

    public function __construct($code, $class, $baseControllerName, EventDispatcherInterface $eventDispatcher)
    {
        $event = new AdminEvent(['studentClassRepository' => &$this->studentClassRepository]);
        $eventDispatcher->dispatch(AdminEvent::STUDENT_ADMIN_PRE_CREATE, $event);
        parent::__construct($code, $class, $baseControllerName);
    }

    public function getStudentClassRepository()
    {
        return $this->studentClassRepository;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        $collection->add('list', 'list');
        $collection->add('create', 'create');
        $collection->add('edit', 'edit/{id}');
        $collection->add('delete', 'delete/{id}');
    }

    protected function configureBatchActions($actions)
    {
    }
}