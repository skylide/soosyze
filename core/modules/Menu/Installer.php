<?php

namespace SoosyzeCore\Menu;

use Psr\Container\ContainerInterface;
use Queryflatfile\TableBuilder;

class Installer implements \SoosyzeCore\System\Migration
{
    public function getDir()
    {
        return __DIR__;
    }

    public function install(ContainerInterface $ci)
    {
        $ci->schema()
            ->createTableIfNotExists('menu', function (TableBuilder $table) {
                $table->string('name')
                ->string('title')
                ->text('description');
            })
            ->createTableIfNotExists('menu_link', function (TableBuilder $table) {
                $table->increments('id')
                ->string('key')->nullable()
                ->string('icon')->nullable()
                ->string('link')
                ->string('query')->nullable()
                ->string('fragment')->nullable()
                ->string('title_link')
                ->string('target_link')->valueDefault('_self')
                ->string('menu')
                ->integer('weight')->valueDefault(1)
                ->integer('parent')
                ->boolean('active')->valueDefault(true);
            });
            
        $ci->query()
            ->insertInto('menu', [ 'name', 'title', 'description' ])
            ->values([ 'menu-admin', 'Administration menu', 'Le menu pour la gestion du site.' ])
            ->values([ 'menu-main', 'Main Menu', 'Main menu of the site.' ])
            ->values([ 'menu-user', 'User Menu', 'User links menu.' ])
            ->execute();
        
        $ci->query()
            ->insertInto('menu_link', [
                'key', 'icon', 'title_link', 'link', 'menu', 'weight', 'parent', 'target_link'
            ])
            ->values([
                'menu.show', 'fa fa-bars', 'Menu', 'admin/menu/menu-main', 'menu-admin', 3, -1, '_self'
            ])
            ->execute();
    }

    public function seeders(ContainerInterface $ci)
    {
        $ci->query()
            ->insertInto('menu_link', [
                'key', 'icon', 'title_link', 'link', 'menu', 'weight', 'parent', 'target_link'
            ])
            ->values([
                null, null, 'Soosyze website', 'https://soosyze.com', 'menu-main', 50, -1, '_blank'
            ])
            ->execute();
    }

    public function hookInstall(ContainerInterface $ci)
    {
        $this->hookInstallUser($ci);
    }

    public function hookInstallUser(ContainerInterface $ci)
    {
        if ($ci->module()->has('User')) {
            $ci->query()
                ->insertInto('role_permission', [ 'role_id', 'permission_id' ])
                ->values([ 3, 'menu.administer' ])
                ->execute();
        }
    }

    public function uninstall(ContainerInterface $ci)
    {
        $ci->schema()->dropTable('menu_link');
        $ci->schema()->dropTable('menu');
    }

    public function hookUninstall(ContainerInterface $ci)
    {
        $this->hookUninstallBlock($ci);
        $this->hookUninstallUser($ci);
    }

    public function hookUninstallBlock(ContainerInterface $ci)
    {
        if ($ci->module()->has('Block')) {
            $ci->query()
                ->from('block')
                ->delete()
                ->where('hook', 'like', 'menu.%')
                ->execute();
        }
    }

    public function hookUninstallUser(ContainerInterface $ci)
    {
        if ($ci->module()->has('User')) {
            $ci->query()
                ->from('role_permission')
                ->delete()
                ->where('permission_id', 'like', 'menu.%')
                ->execute();
        }
    }
}
