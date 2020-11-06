<?php

namespace ConfrariaWeb\Church\Databases\Seeds;

use App\Models\User;
use ConfrariaWeb\Account\Models\Account;
use ConfrariaWeb\Account\Models\Plan;
use ConfrariaWeb\Acl\Models\Permission;
use ConfrariaWeb\Acl\Models\Role;
use ConfrariaWeb\Domain\Models\Domain;
use ConfrariaWeb\Post\Models\PostCategory;
use ConfrariaWeb\Post\Models\PostSection;
use ConfrariaWeb\Site\Models\Site;
use ConfrariaWeb\Template\Models\Template;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ChurchSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*** Account ***/
        $plan = Plan::firstOrCreate(['status' => 1, 'name' => 'Free', 'description' => 'Plano free', 'price' => 0]);
        $account = Account::firstOrCreate(['plan_id' => $plan->id], ['status' => 1, 'plan_id' => 1]);

        /*** ACL ***/
        $role = Role::firstOrCreate(['name' => 'administrator', 'display_name' => 'Administrador', 'description' => 'Administrador do sistema']);
        $permission = Permission::firstOrCreate(['name' => 'admin.roles.index', 'display_name' => 'Lista de perfis', 'description' => 'Lista de perfis']);
        $role->permissions()->sync($permission->id);

        /*** User ***/
        $user = User::firstOrCreate(['email' => 'confrariaweb@gmail.com'], ['name' => 'Rafael Zingano', 'email' => 'confrariaweb@gmail.com', 'password' => Hash::make('secret'), 'email_verified_at' => Carbon::now(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        $user->roles()->sync($role->id);
        $users = User::factory()->count(20)->create();

        /*** Post ***/
        $postSectionBlog = PostSection::firstOrCreate(['user_id' => $user->id, 'name' => 'Blog', 'slug' => 'blog']);
        $postSectionPage = PostSection::firstOrCreate(['user_id' => $user->id, 'name' => 'Page', 'slug' => 'page']);
        $postSectionTestimonial = PostSection::firstOrCreate(['user_id' => $user->id, 'name' => 'Testimonials', 'slug' => 'testimonials']);
        $postCategoryUndefined = PostCategory::firstOrCreate(['user_id' => $user->id, 'name' => 'Undefined', 'slug' => 'undefined']);
        $postCategoryUndefined->sections()->sync($postSectionBlog->id);

        /*** Domain ***/
        $domainA = Domain::firstOrCreate(['domain' => 'localhost'], ['user_id' => $user->id]);
        $domains[] = $domainA->id;
        $domainB = Domain::firstOrCreate(['domain' => 'confrariachurch.com'], ['user_id' => $user->id]);
        $domains[] = $domainB->id;

        /*** Template ***/
        $template = Template::firstOrCreate(['user_id' => $user->id, 'title' => 'Colugo', 'slug' => 'colugo', 'load_views_from' => 'templateColugo', 'service' => 'TemplateColugoService', 'status' => true]);
        $templateChurch = Template::firstOrCreate(['user_id' => $user->id, 'title' => 'Milo', 'slug' => 'milo', 'load_views_from' => 'templateMilo', 'service' => 'TemplateMiloService', 'status' => true]);

        /*** Site ***/
        $site = Site::firstOrCreate(['user_id' => $user->id, 'template_id' => $template->id, 'title' => 'Site Church', 'status' => true]);
        if (isset($domains)) {
            $site->domains()->sync($domains);
        }

    }

}
