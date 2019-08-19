<?php

namespace SoosyzeCore\Block;

use Psr\Container\ContainerInterface;
use Queryflatfile\TableBuilder;

class Installer implements \SoosyzeCore\System\Migration
{
    public function getComposer()
    {
        return __DIR__ . '/composer.json';
    }

    public function install(ContainerInterface $ci)
    {
        $ci->schema()
            ->createTableIfNotExists('block', function (TableBuilder $table) {
                $table->increments('block_id')
                ->string('title')
                ->string('section')
                ->text('content')->nullable()
                ->boolean('hook')->nullable()
                ->integer('weight')
                ->boolean('visibility_pages')->valueDefault(false)
                ->string('pages')->valueDefault('admin/%' . PHP_EOL . 'user/%')
                ->boolean('visibility_roles')->valueDefault(true)
                ->string('roles')->valueDefault('1,2');
            });
    }

    public function seeders(ContainerInterface $ci)
    {
        $ci->query()
            ->insertInto('block', [ 'section', 'title', 'content', 'weight' ])
            ->values([ 'sidebar', 'Lorem ipsum dolor', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce mattis ornare libero, nec faucibus ex pharetra vel. In porta pretium congue. Phasellus mattis arcu vehicula purus sodales, lacinia eleifend risus sagittis. ',
                1 ])
            ->values([ 'sidebar', 'Pellentesque eget', 'Pellentesque eget consectetur libero. Morbi viverra non dolor ac tincidunt. Quisque et enim ut felis dignissim facilisis a vel orci.',
                1 ])
            ->values([ 'footer', '', '<p>Power by <a href="https://soosyze.com">SoosyzeCMS</a></p>',
                1 ])
            ->execute();
    }

    public function hookInstall(ContainerInterface $ci)
    {
        $this->hookInstallMenu($ci);
        $this->hookInstallUser($ci);
    }

    public function hookInstallMenu(ContainerInterface $ci)
    {
        if ($ci->module()->has('Menu')) {
            $ci->query()
                ->insertInto('menu_link', [
                    'key', 'icon', 'title_link', 'link', 'menu', 'weight', 'parent'
                ])
                ->values([
                    'section.admin', 'fa fa-columns', 'Bloc', 'admin/section/theme',
                    'menu-admin', 7, -1
                ])
                ->execute();
        }
    }

    public function hookInstallUser(ContainerInterface $ci)
    {
        if ($ci->module()->has('User')) {
            $ci->query()
                ->insertInto('role_permission', [ 'role_id', 'permission_id' ])
                ->values([ 3, 'block.administer' ])
                ->values([ 3, 'block.created' ])
                ->values([ 3, 'block.edited' ])
                ->values([ 3, 'block.deleted' ])
                ->execute();
        }
    }

    public function uninstall(ContainerInterface $ci)
    {
        $ci->schema()->dropTableIfExists('block');
    }

    public function hookUninstall(ContainerInterface $ci)
    {
        $this->hookUninstallMenu($ci);
        $this->hookUninstallUser($ci);
    }

    public function hookUninstallMenu(ContainerInterface $ci)
    {
        if ($ci->module()->has('Menu')) {
            $ci->query()
                ->from('menu_link')
                ->delete()
                ->where('link', 'like', 'admin/section%')
                ->orWhere('link', 'like', 'admin/block%')
                ->execute();
        }
    }

    public function hookUninstallUser(ContainerInterface $ci)
    {
        if ($ci->module()->has('User')) {
            $ci->query()
                ->from('role_permission')
                ->delete()
                ->where('permission_id', 'like', 'block.%')
                ->execute();
        }
    }
}