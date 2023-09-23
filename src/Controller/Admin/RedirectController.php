<?php

namespace PrestaShop\Module\Ps_Redirect\Controller\Admin;

use Meta;
use PrestaShop\Module\Ps_Redirect\Filters\RulesFilters;
use PrestaShop\Module\Ps_Redirect\Form\RuleFormDataProvider;
use PrestaShop\Module\Ps_Redirect\Grid\RulesDefinitionFactory;
use PrestaShop\Module\Ps_Redirect\Model\Rule;
use PrestaShop\PrestaShop\Adapter\Entity\Db;
use PrestaShop\PrestaShop\Core\Form\FormHandlerInterface;
use PrestaShop\PrestaShop\Core\Grid\Grid;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteria;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Service\Grid\ResponseBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by 40x.Pro@gmail.com
 * Date: 13.09.2023
 */
class RedirectController extends FrameworkBundleAdminController
{

    public function listAction(Request $request, RulesFilters $filters)
    {
        $rulesGridFactory = $this->get('prestashop.module.ps_redirect.grid.rules_grid_factory');
        /** @var Grid $rulesGrid */
        $rulesGrid = $rulesGridFactory->getGrid($filters);

        return $this->render('@Modules/ps_redirect/views/templates/admin/list.html.twig', [
            'rulesGrid' => $this->presentGrid($rulesGrid),
            'layoutHeaderToolbarBtn' => $this->getToolbarButtons(),
        ]);
    }

    public function searchAction(Request $request)
    {
        /** @var ResponseBuilder $responseBuilder */
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
            $this->get('prestashop.module.ps_redirect.grid.rules_definition_factory'),
            $request,
            RulesDefinitionFactory::GRID_ID,
            'admin_ps_redirect_list'
        );
    }

    public function createAction(Request $request)
    {
        /** @var RuleFormDataProvider $formProvider */
        $formProvider = $this->get('prestashop.module.ps_redirect.form.rule_form_provider');
        $formProvider->setIdRule(null);

        $form = $this->get('prestashop.module.ps_redirect.form.rule_form_handler')->getForm();

        return $this->render('@Modules/ps_redirect/views/templates/admin/form.html.twig', [
            'ruleForm' => $form->createView(),
        ]);
    }

    public function editAction(Request $request, $ruleId)
    {
        /** @var RuleFormDataProvider $formProvider */
        $formProvider = $this->get('prestashop.module.ps_redirect.form.rule_form_provider');
        $formProvider->setIdRule($ruleId);

        $form = $this->get('prestashop.module.ps_redirect.form.rule_form_handler')->getForm();

        return $this->render('@Modules/ps_redirect/views/templates/admin/form.html.twig', [
            'ruleForm' => $form->createView(),
        ]);
    }

    public function bulkEnableStatusAction(Request $request)
    {
        if (Db::getInstance()->update(
            'redirect_rules', ['active' => true],
            'rule_id in (' . implode(',', $request->get('rules_bulk')) . ')'
        )) {
            $this->addFlash('success', $this->trans('Status enabled successful.', 'Admin.Notifications.Success'));
        }

        return $this->redirectToRoute('admin_ps_redirect_list');
    }

    public function bulkDisableStatusAction(Request $request)
    {
        if (Db::getInstance()->update(
            'redirect_rules', ['active' => false],
            'rule_id in (' . implode(',', $request->get('rules_bulk')) . ')'
        )) {
            $this->addFlash('success', $this->trans('Status disable successful.', 'Admin.Notifications.Success'));
        }

        return $this->redirectToRoute('admin_ps_redirect_list');
    }


    public function bulkDeleteAction(Request $request)
    {
        if (Db::getInstance()->delete(
            'redirect_rules',
            'rule_id in (' . implode(',', $request->get('rules_bulk')) . ')'
        )) {
            $this->addFlash('success', $this->trans('Rules deleted successful.', 'Admin.Notifications.Success'));
        }

        return $this->redirectToRoute('admin_ps_redirect_list');
    }

    public function toggleStatusAction(Request $request, $ruleId)
    {
        $rule = new Rule($ruleId);
        if ($rule->id) {
            $rule->active = !$rule->active;
            $rule->save();

            $response = [
                'status' => true,
                'message' => $this->trans('The status has been successfully updated.', 'Admin.Notifications.Success'),
            ];
        } else {
            $response = [
                'status' => false,
                'message' => $this->trans('The rule doesn\'t exist.', 'Admin.Notifications.Success'),
            ];
        }

        return $this->json($response);
    }

    public function deleteAction(Request $request, $ruleId)
    {
        $errors = [];

        $rule = new Rule($ruleId);
        if ($rule->rule_id) {
            $rule->delete();
        } else {
            $errors[] = [
                'key' => 'Could not delete #%i',
                'domain' => 'Admin.Catalog.Notification',
                'parameters' => [$ruleId],
            ];
        }

        if (0 === count($errors)) {
            $this->addFlash('success', $this->trans('Successful deletion.', 'Admin.Notifications.Success'));
        } else {
            $this->flashErrors($errors);
        }

        return $this->redirectToRoute('admin_ps_redirect_list');
    }

    public function createProcessAction(Request $request)
    {
        return $this->processForm($request, 'Successful creation.');
    }

    public function editProcessAction(Request $request, $ruleId)
    {
        return $this->processForm($request, 'Successful update.', $ruleId);
    }

    private function processForm(Request $request, $successMessage, $ruleId = null)
    {
        /** @var RuleFormDataProvider $formProvider */
        $formProvider = $this->get('prestashop.module.ps_redirect.form.rule_form_provider');
        $formProvider->setIdRule($ruleId);

        /** @var FormHandlerInterface $formHandler */
        $formHandler = $this->get('prestashop.module.ps_redirect.form.rule_form_handler');
        $form = $formHandler->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $saveErrors = $formHandler->save($form->getData());
                if (0 === count($saveErrors)) {
                    $this->addFlash('success', $this->trans($successMessage, 'Admin.Notifications.Success'));

                    return $this->redirectToRoute('admin_ps_redirect_list');
                }

                $this->flashErrors($saveErrors);
            }
            $formErrors = [];
            foreach ($form->getErrors(true) as $error) {
                $formErrors[] = $error->getMessage();
            }
            $this->flashErrors($formErrors);
        }

        return $this->render('@Modules/ps_redirect/views/templates/admin/form.html.twig', [
            'ruleForm' => $form->createView(),
        ]);
    }

    private function getToolbarButtons()
    {
        return [
            'add' => [
                'href' => $this->generateUrl('admin_ps_redirect_create'),
                'desc' => $this->trans('New rule', 'Modules.Ps_Redirect.Admin'),
                'icon' => 'add_circle_outline',
            ],
        ];
    }

}
