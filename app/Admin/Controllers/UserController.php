<?php

namespace App\Admin\Controllers;

use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('type', __('Type'))->display(function($type){
            if($type == 1){
                return 'user';
            }
            else if($type = '2'){
                return 'restaurant';
            }
            else if($type = '3') {
                return 'cafe';

            }
            });
        $grid->column('description_ar', __('Description ar'));
        $grid->column('description_en', __('Description en'));
        $grid->column('available', __('Available'));
        $grid->column('points', __('Points'));
        $grid->column('address', __('Address'));
        $grid->column('phone', __('Phone'));
        $grid->column('image', __('Image'))->display(function($url){
            return "<image src=\"/images/$url\" width=\"50\"/>";


        });
        $grid->column('website', __('Website'));
        $grid->column('lng', __('Lng'));
        $grid->column('lat', __('Lat'));
        $grid->column('renew_date', __('Renew date'));
        $grid->column('start_working', __('Start working'));
        $grid->column('end_working', __('End working'));
        $grid->column('email_verified_at', __('Email verified at'));
        $grid->column('password', __('Password'));
        $grid->column('country', __('Country'));
        $grid->column('verified', __('Verified'))->display(function($type){
            if($type == 1){
                return 'yes';
            }
            else {
                return 'no';
            }

        });
        $grid->column('verify_code', __('Verify code'));
        $grid->column('invite_code', __('Invite code'));
        $grid->column('invited_code', __('Invited code'));
        $grid->column('place', __('Place'));
        $grid->column('smoking', __('Smoking'))->display(function($type){
            if($type == 1){
                return 'yes';
            }
            else {
                return 'no';
            }

        });
        $grid->column('outt', __('Outt'))->display(function($type){
            if($type == 1){
                return 'yes';
            }
            else {
                return 'no';
            }

        });
        $grid->column('remember_token', __('Remember token'));
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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('type', __('Type'));
        $show->field('description_ar', __('Description ar'));
        $show->field('description_en', __('Description en'));
        $show->field('available', __('Available'));
        $show->field('points', __('Points'));
        $show->field('address', __('Address'));
        $show->field('phone', __('Phone'));
        $show->field('image', __('Image'));
        $show->field('website', __('Website'));
        $show->field('lng', __('Lng'));
        $show->field('lat', __('Lat'));
        $show->field('renew_date', __('Renew date'));
        $show->field('start_working', __('Start working'));
        $show->field('end_working', __('End working'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('password', __('Password'));
        $show->field('country', __('Country'));
        $show->field('verified', __('Verified'));
        $show->field('verify_code', __('Verify code'));
        $show->field('invite_code', __('Invite code'));
        $show->field('invited_code', __('Invited code'));
        $show->field('place', __('Place'));
        $show->field('smoking', __('Smoking'));
        $show->field('outt', __('Outt'));
        $show->field('remember_token', __('Remember token'));
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
        $form = new Form(new User());

        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        $form->select('type', __('Type'))->options([1 => 'user', 2 => 'restaurant',3=>'cafe']);
        $form->textarea('description_ar', __('Description ar'));
        $form->textarea('description_en', __('Description en'));
        $form->number('available', __('Available'));
        $form->number('points', __('Points'));
        $form->textarea('address', __('Address'));
        $form->textarea('phone', __('Phone'));
        $form->image('image', __('Image'));
        $form->text('website', __('Website'));
        $form->latlong('lat','lng');
        $form->date('renew_date', __('Renew date'))->default(date('Y-m-d'));
        $form->time('start_working', __('Start working'))->default(date('H:i:s'));
        $form->time('end_working', __('End working'))->default(date('H:i:s'));
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->password('password', __('Password'));
        $form->text('country', __('Country'));
        $form->select('verified', __('Verified'))->options([0 => 'no', 1 => 'yes']);;
        $form->number('verify_code', __('Verify code'));
        $form->text('invite_code', __('Invite code'));
        $form->text('invited_code', __('Invited code'));
        $form->number('place', __('Place'));
        $form->select('smoking', __('Smoking'))->options([0 => 'no', 1 => 'yes']);
        $form->select('outt', __('Outt'))->options([0 => 'no', 1 => 'yes']);
        $form->text('remember_token', __('Remember token'));

        return $form;
    }
}
