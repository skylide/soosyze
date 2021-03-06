<?php

namespace SoosyzeCore\Menu\Services;

class HookApp
{
    protected $menu;

    public function __construct($menu)
    {
        $this->menu = $menu;
    }

    public function hookResponseAfter($request, &$response)
    {
        if ($response instanceof \SoosyzeCore\Template\Services\Templating) {
            $nameMenu = $response->isTheme('theme')
                ? 'menu-main'
                : 'menu-admin';

            $response
                ->addBlock('page.main_menu', $this->menu->renderMenu($nameMenu))
                ->addBlock('page.second_menu', $this->menu->renderMenu('menu-user'));
        }
    }

    public function hookMenuShowResponseAfter($request, &$response)
    {
        if ($response instanceof \SoosyzeCore\Template\Services\Templating) {
            $script  = $response->getBlock('this')->getVar('scripts');
            $script .= '<script>
            $().ready(function () {
                var nestedSortables = [].slice.call($(\'.nested-sortable\'));

                for (var i = 0; i < nestedSortables.length; i++) {
                    new Sortable(nestedSortables[i], {
                        group: "nested",
                        animation: 150,
                        fallbackOnBody: true,
                        swapThreshold: 0.1,
                        ghostClass: "placeholder",
                        dragoverBubble: true,
                        onEnd: function (evt) {
                            render("#main_sortable");
                        }
                    });
                }

                function render(idMenu) {
                    var weight = 1;
                    var id = $(idMenu).parent("li").children(\'input[name^="id"]\').val();
                    if (id === undefined) {
                        id = -1;
                    }
                    $(idMenu).children("li").each(function () {
                        $(this).children(\'input[name^="weight"]\').val(weight);
                        $(this).children(\'input[name^="parent"]\').val(id);
                        render($(this).children("ol"));
                        weight++;
                    });
                }
            });
            </script>';

            $response->view('this', [
                'scripts' => $script
            ]);
        }
    }
}
