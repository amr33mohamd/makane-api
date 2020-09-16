<?php

namespace App\Admin\Controllers;

use App\Reservations;
use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
// status 0 -> waiting 1->done 2->ignored 3->canceled

class ReservationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Reservations';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Reservations());

        $grid->column('id', __('Id'));
        $grid->column('store_id', __('Store id'))->display(function ($id){
            return User::where('id',$id)->first()->name;
        });
        $grid->column('customer_id', __('Customer id'))->display(function ($id){
            return User::where('id',$id)->first()->email;
        });
        $grid->column('type', __('Type'));
        $grid->column('SpecialEvent_id', __('SpecialEvent id'));
        $grid->column('status', __('Status'))->display(function($type){
            if($type == 0){
                return 'waiting';
            }
            else if($type == 1) {
                return 'done';
            }
            else if($type == 2) {
                return 'ignored';
            } else if($type == 3) {
                return 'canceled';
            }

        });
        $grid->column('time', __('Time'));
        $grid->column('persons', __('Persons'));
        $grid->column('kids', __('Kids'));
        $grid->column('smoking', __('Smoking'));
        $grid->column('outt', __('Outt'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Reservations::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('store_id', __('Store id'));
        $show->field('customer_id', __('Customer id'));
        $show->field('type', __('Type'));
        $show->field('SpecialEvent_id', __('SpecialEvent id'));
        $show->field('status', __('Status'));
        $show->field('time', __('Time'));
        $show->field('persons', __('Persons'));
        $show->field('kids', __('Kids'));
        $show->field('smoking', __('Smoking'));
        $show->field('outt', __('Outt'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Reservations());

        $form->number('store_id', __('Store id'));
        $form->number('customer_id', __('Customer id'));
        $form->number('type', __('Type'))->default(1);
        $form->number('SpecialEvent_id', __('SpecialEvent id'));
        $form->number('status', __('Status'));
        $form->time('time', __('Time'))->default(date('H:i:s'));
        $form->number('persons', __('Persons'));
        $form->number('kids', __('Kids'));
        $form->number('smoking', __('Smoking'));
        $form->number('outt', __('Outt'));

        return $form;
    }
}
