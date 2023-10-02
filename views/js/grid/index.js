import Grid from '@components/grid/grid';
import LinkRowActionExtension from '@components/grid/extension/link-row-action-extension';
import SubmitRowActionExtension from '@components/grid/extension/action/row/submit-row-action-extension';
import SortingExtension from "@components/grid/extension/sorting-extension";
import ReloadListExtension from "@components/grid/extension/reload-list-extension";
import AsyncToggleColumnExtension from "@components/grid/extension/column/common/async-toggle-column-extension";
import FiltersResetExtension from "@components/grid/extension/filters-reset-extension";
import FiltersSubmitButtonEnablerExtension from "@components/grid/extension/filters-submit-button-enabler-extension";
import BulkActionCheckboxExtension from "@components/grid/extension/bulk-action-checkbox-extension";
import SubmitBulkExtension from "@components/grid/extension/submit-bulk-action-extension";

const $ = window.$;

$(() => {
  let gridDivs = document.querySelectorAll('.js-grid');
  gridDivs.forEach((gridDiv) => {
    const grid = new Grid(gridDiv.dataset.gridId);

    grid.addExtension(new SubmitBulkExtension());
    grid.addExtension(new LinkRowActionExtension());
    grid.addExtension(new SubmitRowActionExtension());
    grid.addExtension(new ReloadListExtension());
    grid.addExtension(new SortingExtension());
    grid.addExtension(new AsyncToggleColumnExtension());
    grid.addExtension(new FiltersResetExtension());
    grid.addExtension(new FiltersSubmitButtonEnablerExtension());
    grid.addExtension(new BulkActionCheckboxExtension());
  });
});
