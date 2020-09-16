<?php

namespace App\Admin\Controllers;

use App\Coupons;
use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CouponsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Coupons';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Coupons());

        $grid->column('id', __('Id'));
        $grid->column('store_id', __('Store id'))->display(function ($id){
            return User::where('id',$id)->first()->name;
        });;
        $grid->column('code', __('Code'));
        $grid->column('percent', __('Percent'));
        $grid->column('price', __('Price'));
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
        $show = new Show(Coupons::findOrFail($id));

        $show->field('id', __('Id'));
        $show->select('store_id', __('Store id'))->options(User::where(['type','!=','1'])->pluck('email', 'id'));;
        $show->field('code', __('Code'));
        $show->field('percent', __('Percent'));
        $show->field('price', __('Price'));
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
        $form = new Form(new Coupons());

        $form->select('store_id', __('Store id'))->options(User::where('type','!=','1')->pluck('name', 'id'));;
        $form->text('code', __('Code'));
        $form->text('percent', __('Percent'));
        $form->decimal('price', __('Price'));

        return $form;
    }
}
