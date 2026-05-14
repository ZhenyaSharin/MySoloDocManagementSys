/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Promise = require('es6-promise').Promise;

window.Vue = require('vue').default;

window.events = new Vue();

window.flash = function(message) {
    window.events.$emit('flash', message);
}

import Multiselect from 'vue-multiselect';

import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';

import 'vue2-datepicker/locale/ru';

// import '../sass/vue2-multiselect_custom.scss';

// import Vue from 'vue';
import VueRouter from 'vue-router'; 
import HomePage from './components/HomePage.vue';
import UserProfileModal from './components/modals/UserProfile.vue';
import PreviewFrame from './components/templates/PreviewFrame.vue';
import DocumentPage from './components/DocumentPage.vue';
import AssignmentPage from './components/AssignmentPage.vue';
import UserPage from './components/UserPage.vue';
import AnalyticsPage from './components/AnalyticsPage.vue';
import SearchPage from './components/SearchPage.vue';
import InfoPage from './components/InfoPage.vue';

import DocumentsList from './components/lists/DocumentsList.vue';
import AgreementsList from './components/lists/AgreementsList.vue';
import AssignmentsList from './components/lists/AssignmentsList.vue';
import NewAgreementsList from './components/lists/NewAgreementsList.vue';
import HistoryList from './components/lists/HistoryList.vue';
import AcquaintancesList from './components/lists/AcquaintancesList.vue';
import OutlettersList from './components/lists/OutlettersList.vue';
import ContractsList from './components/lists/ContractsList.vue';
import OrdersODList from './components/lists/OrdersODList.vue';
import MemosList from './components/lists/MemosList.vue';
import OtherDocsList from './components/lists/OtherDocsList.vue';
import NotificationsList from './components/lists/NotificationsList.vue';
import ORDocsList from './components/lists/ORDocsList.vue';
import AddAgreementsList from './components/lists/AddAgreementsList.vue';
import SOCsList from './components/lists/SOCsList.vue';
import LettersList from './components/lists/LettersList.vue';
import ArchivedocsList from './components/lists/ArchivedocsList.vue';

import AgreementsListPdf from './components/pdf/AgreementsListPdf.vue';
import AgreementsListModal from './components/modals/AgreementsListModal.vue';

import DocumentsListItem from './components/items/DocumentItem.vue';
import ResponsesListItem from './components/items/ResponseItem.vue';
import AgreementsListItem from './components/items/AgreementItem.vue';
import HistoryLogItem from './components/items/HistoryLogItem.vue';
import AdminUsersListItem from './components/items/AdminUsersListItem.vue';
import LettersListItem from './components/items/LetterItem.vue';
import AssignmentAuthorItem from './components/items/AssignmentAuthorItem.vue';
import AssignmentExecutorItem from './components/items/AssignmentExecutorItem.vue';
import AgreersDocpageItem from './components/items/AgreersDocpageItem.vue';
import BaseItem from './components/items/BaseItem.vue';
import AcquaintancesListItem from './components/items/AcquaintancesListItem.vue';
import AssignmentPageExecutorItem from './components/items/AssignmentPageExecutorItem.vue';
import AssignmentControlItem from './components/items/AssignmentControlItem.vue';
import AssignmentAuthorMultiItem from './components/items/AssignmentAuthorMultiItem.vue';
import AssignmentExecutorMultiItem from './components/items/AssignmentExecutorMultiItem.vue';
import SearchAssignmentItem from './components/items/SearchAssignmentItem.vue';
import SearchAssignmentShortItem from './components/items/SearchAssignmentShortItem.vue';
import SearchDocumentItem from './components/items/SearchDocumentItem.vue';
import SearchDocumentShortItem from './components/items/SearchDocumentShortItem.vue';
import RelationItem from './components/items/RelationItem.vue';


import SendAgainModal from './components/modals/SendAgainAgreement.vue';
import DocStatusLogModal from './components/modals/DocStatusLogModal.vue';
import AddInArchiveModal from './components/modals/AddInArchiveModal.vue';
import DeleteDocModal from './components/modals/DeleteDocModal.vue';
import DeleteAgreeModal from './components/modals/DeleteAgreeModal.vue';
import AssignExecutorModal from './components/modals/AssignExecutorModal.vue';
import AcquaintanceListPageModal from './components/modals/AcquaintanceListPageModal.vue';
import RefuseAssignmentModal from './components/modals/RefuseAssignmentModal.vue';
import AssignStatusLogModal from './components/modals/AssignStatusLogModal.vue';
import DeleteAssignAuthorModal from './components/modals/DeleteAssignAuthorModal.vue';
import RefuseDocumentModal from './components/modals/RefuseDocumentModal.vue';
import DocumentImageModal from './components/modals/DocumentImageModal.vue';
import PersonalControlModal from './components/modals/PersonalControlModal.vue';
import UserMailSettingsModal from './components/modals/UserMailSettingsModal.vue';
import RefuseAssignmentDeadlineModal from './components/modals/RefuseAssignmentDeadlineModal.vue';
import DeadlinesLogModal from './components/modals/DeadlinesLogModal.vue';
import DeleteAdditionModal from './components/modals/DeleteAdditionModal.vue';
import DeleteAdditionFileModal from './components/modals/DeleteAdditionFileModal.vue';
import EditAgreementUserListModal from './components/modals/EditAgreementUserListModal.vue';
import SaveDocInfoModal from './components/modals/SaveDocInfoModal.vue';
import SaveAssignInfoModal from './components/modals/SaveAssignInfoModal.vue';
import GetFromArchiveModal from './components/modals/GetFromArchiveModal.vue';
import SaveDocFileModal from './components/modals/SaveDocFileModal.vue';
import AssignmentsMultiListModal from './components/modals/AssignmentsMultiListModal.vue';
import RelationsListModal from './components/modals/RelationsListModal.vue';
import DeleteRelationModal from './components/modals/DeleteRelationModal.vue';
import UserRolesListModal from './components/modals/UserRolesListModal.vue';

import Spinner from './components/templates/Spinner.vue';
import AdminPanel from './components/templates/AdminPanel.vue';
import BlockedPanel from './components/templates/BlockedPanel.vue';
import NewDocumentTemplate from './components/templates/NewDocumentTemplate.vue';
import NewAssignmentTemplate from './components/templates/NewAssignmentTemplate.vue';
import NewUserTemplate from './components/templates/NewUserTemplate.vue';
import BaseDocsAssignmentsTemplate from './components/templates/BaseDocsAssignments.vue';
import AgreersTemplate from './components/templates/AgreersTemplate.vue';
import ListCountTemplate from './components/templates/ListCountTemplate.vue';
import AddDepartmentTemplate from './components/templates/AddDepartmentTemplate.vue';
import SearchTemplate from './components/templates/SearchTemplate.vue';
import NewAdditionTemplate from './components/templates/NewAdditionTemplate.vue';
import AdditionTemplate from './components/templates/AdditionTemplate.vue';
import AcquaintanceSendTemplate from './components/templates/AcquaintanceSendTemplate.vue';
import EditDocfileTemplate from './components/templates/EditDocfileTemplate.vue';
import AgreementListPreviewTemplate from './components/templates/AgreementListPreviewTemplate.vue';
// AgreementListPreviewTemplate
import DiruserAddTemplate from './components/templates/DiruserAddTemplate.vue';
import ListPaginationTemplate from './components/templates/ListPaginationTemplate.vue';
import ChoosePaginationTemplate from './components/templates/ChoosePaginationTemplate.vue';
import AdminRegTemplate from './components/templates/AdminRegTemplate.vue';
import RelationsAddTemplate from './components/templates/RelationsAddTemplate.vue';
import MultiselectListTemplate from './components/templates/MultiselectListTemplate.vue';
import RelationsListTemplate from './components/templates/RelationsListTemplate.vue';
// AdminRegTemplate

import DocStatusTd from './components/elements/DocStatusTd.vue';
import InWorkAlert from './components/elements/InWorkAlert.vue';
import UserFioLink from './components/elements/UserFioLink.vue';
import AssignStatusTd from './components/elements/AssignStatusTd.vue';
import DateLeftTd from './components/elements/DateLeftTd.vue';
import FormattedTitleTd from './components/elements/FormattedTitleTd.vue';
import DocFileLink from './components/elements/DocFileLink.vue';
import DirusersSelect from './components/elements/DirusersSelect.vue';
import DocAssignSelectAlert from './components/elements/DocAssignSelectAlert.vue';
import DocsbytypeTbody from './components/elements/DocsbytypeTbody.vue';
import IndexTd from './components/elements/IndexTd.vue';
import NotFoundTr from './components/elements/NotFoundTr.vue';
import DocsListThead from './components/elements/DocsListThead.vue';
import BackListLink from './components/elements/BackListLink.vue';
import DocsbytypeTable from './components/elements/DocsbytypeTable.vue';


import TimestampElement from './components/elements/TimestampElement.vue';
import PreviewPDFRowElement from './components/elements/PreviewPDFRowElement.vue';
import DateToggleElement from './components/elements/DateToggleElement.vue';
import AscDescElement from './components/elements/AscDescElement.vue';
// PreviewPDFRowElement

import Blog from './components/Blog.vue';
import Feedback from './components/Feedback.vue';

import DocumentAgreementMail from './components/emails/DocumentAgreementMail.vue';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

window.Vue.use(VueRouter);

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('vue-panel-admin', AdminPanel);
Vue.component('vue-panel-blocked', BlockedPanel);

Vue.component('vue-spinner', Spinner);
Vue.component('vue-page-home', HomePage);
Vue.component('vue-page-doc', DocumentPage);
Vue.component('vue-page-assign', AssignmentPage);
Vue.component('vue-page-user', UserPage);
Vue.component('vue-page-analytics', AnalyticsPage);
Vue.component('vue-page-search', SearchPage);
Vue.component('vue-page-info', InfoPage);
// AnalyticsPage

Vue.component('vue-list-documents', DocumentsList);
Vue.component('vue-list-agreements', AgreementsList);
Vue.component('vue-list-assignments', AssignmentsList);
Vue.component('vue-list-newagreements', NewAgreementsList);
Vue.component('vue-list-history', HistoryList);
Vue.component('vue-list-letters', LettersList);
Vue.component('vue-list-archivedocs', ArchivedocsList);
// ArchivedocsList
Vue.component('vue-list-acquaintances', AcquaintancesList);
Vue.component('vue-list-outletters', OutlettersList);
Vue.component('vue-list-contracts', ContractsList);
Vue.component('vue-list-ordersod', OrdersODList);
Vue.component('vue-list-memos', MemosList);
Vue.component('vue-list-otherdocs', OtherDocsList);
Vue.component('vue-list-notifications', NotificationsList);
Vue.component('vue-list-ordocs', ORDocsList);
Vue.component('vue-list-addagreements', AddAgreementsList);
Vue.component('vue-list-socs', SOCsList);

Vue.component('vue-pdf-agreementslist', AgreementsListPdf);

Vue.component('vue-item-documentitem', DocumentsListItem);
Vue.component('vue-item-responseitem', ResponsesListItem);
Vue.component('vue-item-agreementitem', AgreementsListItem);
Vue.component('vue-item-agreersdocpage', AgreersDocpageItem);
Vue.component('vue-item-historylog', HistoryLogItem);
Vue.component('vue-item-adminuser', AdminUsersListItem);
Vue.component('vue-item-letterlist', LettersListItem);
Vue.component('vue-item-assignment-author', AssignmentAuthorItem);
Vue.component('vue-item-assignment-executor', AssignmentExecutorItem);
Vue.component('vue-item-base', BaseItem);
Vue.component('vue-item-acquaintanceslist', AcquaintancesListItem);
Vue.component('vue-item-assignmentpage-executor', AssignmentPageExecutorItem);
Vue.component('vue-item-assignmentcontrol', AssignmentControlItem);
Vue.component('vue-item-assignment-author-multi', AssignmentAuthorMultiItem);
Vue.component('vue-item-assignment-executor-multi', AssignmentExecutorMultiItem);
Vue.component('vue-item-search-assignment', SearchAssignmentItem);
Vue.component('vue-item-search-assignment-short', SearchAssignmentShortItem);
Vue.component('vue-item-search-document', SearchDocumentItem);
Vue.component('vue-item-search-document-short', SearchDocumentShortItem);
Vue.component('vue-item-relation', RelationItem);


Vue.component('vue-modal-agreementslist', AgreementsListModal);
Vue.component('vue-modal-sendagain', SendAgainModal);
Vue.component('vue-modal-docstatuslog', DocStatusLogModal);
Vue.component('vue-modal-addinarchive', AddInArchiveModal);
Vue.component('vue-modal-assignexecutor', AssignExecutorModal);
Vue.component('vue-modal-acquaintancelist', AcquaintanceListPageModal);
Vue.component('vue-modal-refuseassignment', RefuseAssignmentModal);
Vue.component('vue-modal-assignstatuslog', AssignStatusLogModal);
Vue.component('vue-modal-deleteassignauthor', DeleteAssignAuthorModal);
Vue.component('vue-modal-refusedocument', RefuseDocumentModal);
Vue.component('vue-modal-documentimage', DocumentImageModal);
Vue.component('vue-modal-personalcontrol', PersonalControlModal);
Vue.component('vue-modal-usermailsettings', UserMailSettingsModal);
Vue.component('vue-modal-refuseassigndeadline', RefuseAssignmentDeadlineModal);
Vue.component('vue-modal-deadlineslog', DeadlinesLogModal);
Vue.component('vue-modal-userprofile', UserProfileModal);
Vue.component('vue-modal-deletedoc', DeleteDocModal);
Vue.component('vue-modal-deleteagree', DeleteAgreeModal);
Vue.component('vue-modal-deleteaddition', DeleteAdditionModal);
Vue.component('vue-modal-deletefileaddition', DeleteAdditionFileModal);
Vue.component('vue-modal-editagreeuserlist', EditAgreementUserListModal);
Vue.component('vue-modal-savedocinfo', SaveDocInfoModal);
Vue.component('vue-modal-saveassigninfo', SaveAssignInfoModal);
Vue.component('vue-modal-getfromarchive', GetFromArchiveModal);
Vue.component('vue-modal-savefileinfo', SaveDocFileModal);
Vue.component('vue-modal-assignmentsmultilist', AssignmentsMultiListModal);
Vue.component('vue-modal-relationslist', RelationsListModal);
Vue.component('vue-modal-deleterelation', DeleteRelationModal);
Vue.component('vue-modal-userroleslist', UserRolesListModal);

// GetFromArchiveModal

Vue.component('vue-template-newdocument', NewDocumentTemplate);
Vue.component('vue-template-newassignment', NewAssignmentTemplate);
Vue.component('vue-template-newuser', NewUserTemplate);
Vue.component('vue-template-basedocassign', BaseDocsAssignmentsTemplate);
Vue.component('vue-template-agreers', AgreersTemplate);
Vue.component('vue-template-listcount', ListCountTemplate);
Vue.component('vue-template-search', SearchTemplate);
Vue.component('vue-template-newaddition', NewAdditionTemplate);
Vue.component('vue-template-addition', AdditionTemplate);
Vue.component('vue-template-acquaintancesend', AcquaintanceSendTemplate);
Vue.component('vue-template-editdocfile', EditDocfileTemplate);
Vue.component('vue-template-аgreementpreview', AgreementListPreviewTemplate);
Vue.component('vue-template-diruseradd', DiruserAddTemplate);
Vue.component('vue-template-listpagination', ListPaginationTemplate);
Vue.component('vue-template-choosepagination', ChoosePaginationTemplate);
Vue.component('vue-template-adminreg', AdminRegTemplate);
Vue.component('vue-template-relationsadd', RelationsAddTemplate);
Vue.component('vue-template-multiselectlist', MultiselectListTemplate);
Vue.component('vue-template-relationslist', RelationsListTemplate);

// MultiselectListTemplate

// AdminRegTemplate

Vue.component('vue-frame-preview', PreviewFrame);
Vue.component('vue-td-dateleft', DateLeftTd);
Vue.component('vue-blog', Blog);
Vue.component('vue-feedback', Feedback);
Vue.component('vue-td-assignstatus', AssignStatusTd);
Vue.component('vue-template-departmentadd', AddDepartmentTemplate);
Vue.component('vue-td-docstatus', DocStatusTd);
Vue.component('vue-alert-inwork', InWorkAlert);
Vue.component('vue-link-userfio', UserFioLink);
Vue.component('vue-td-formattedtitle', FormattedTitleTd);
Vue.component('vue-link-docfile', DocFileLink);
Vue.component('vue-select-dirusers', DirusersSelect);
Vue.component('vue-alert-docassignselect', DocAssignSelectAlert);
Vue.component('vue-tbody-docsbytype', DocsbytypeTbody);
Vue.component('vue-td-index', IndexTd);
Vue.component('vue-tr-notfound', NotFoundTr);
Vue.component('vue-thead-docslist', DocsListThead);
Vue.component('vue-link-backlist', BackListLink);
Vue.component('vue-table-docsbytype', DocsbytypeTable);


Vue.component('vue-elem-timestamp', TimestampElement);
Vue.component('vue-elem-previewpdfrow', PreviewPDFRowElement);
Vue.component('vue-elem-datetoggle', DateToggleElement);
Vue.component('vue-elem-ascdesc', AscDescElement);
// AscDescElement

Vue.component('vue-multiselect', Multiselect);
Vue.component('vue-datepicker', DatePicker);

Vue.component('vue-mail-documentagreement', DocumentAgreementMail);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const routes = [
    {
        path: '/',
        name: 'home',
        components: {
            HomePage,
            ResponsesListItem,
            AgreementsListItem,
            HistoryLogItem,
            AdminUsersListItem,
            PreviewFrame,
            NewDocumentTemplate,
            NewAssignmentTemplate,
            AdminPanel,
            NewUserTemplate,
            UserProfileModal,
            DeleteAgreeModal,
            Multiselect,
            BaseDocsAssignmentsTemplate,
            AgreersTemplate,
            AssignmentAuthorItem,
            AssignmentAuthorMultiItem,
            AssignmentExecutorMultiItem,
            LettersListItem,
            AssignmentExecutorItem,
            AssignExecutorModal,
            DateLeftTd,
            ListCountTemplate,
            BlockedPanel,
            AssignmentExecutorItem,
            SendAgainModal,
            // AgreementsList,
            DatePicker,
            AssignStatusTd,
            DeleteAssignAuthorModal,
            AddDepartmentTemplate,
            DocStatusTd,
            AssignmentControlItem,
            InWorkAlert,
            UserFioLink,
            TimestampElement,
            FormattedTitleTd,
            AssignmentsMultiListModal,
            DocAssignSelectAlert,
            DiruserAddTemplate,
            DateToggleElement,
            IndexTd,
            AdminRegTemplate,
            RelationsAddTemplate,
            MultiselectListTemplate,
        }
    },
    {
        path: '/admin',
        name: 'admin',
        components: {
            AdminPanel,
            NewUserTemplate,
            UserProfileModal,
            InWorkAlert,
            UserFioLink,
            TimestampElement,
        }
    },
    {
        path: '/documents',
        name: 'documents',
        component: {
            DocumentsList,
            DocumentsListItem,
            DocStatusTd,
            UserFioLink,
            TimestampElement,
            DateToggleElement,
            ListPaginationTemplate,
            ChoosePaginationTemplate,
            IndexTd,
            NotFoundTr,
            DocsListThead,
        },

    },
    {
        path: '/agreements',
        name: 'agreements',
        component: {
            AgreementsList,
            // AgreementsListItem,
            DeleteAgreeModal,
            ResponsesListItem,
            SendAgainModal,
            UserFioLink,
            TimestampElement,
            ListPaginationTemplate,
            IndexTd,
            RelationsAddTemplate,
        }
    },
    {
        path: '/assignments',
        name: 'assignments',
        component: {
            AgreementsList,
            AssignmentAuthorItem,
            AssignmentAuthorMultiItem,
            AssignmentExecutorItem,
            DateLeftTd,
            AssignStatusTd,
            DeleteAssignAuthorModal,
            UserFioLink,
            TimestampElement,
            FormattedTitleTd,
            AssignmentsMultiListModal,
            AssignmentExecutorMultiItem,
            ListPaginationTemplate,
            IndexTd,
            RelationsAddTemplate,
        },

    },
    {
        path: '/document-:id',
        name: 'document-page',
        components: {
            DocumentPage,
            DocStatusLogModal,
            AddInArchiveModal,
            PreviewFrame,
            // AgreementsListPdf,
            DeleteDocModal,
            AgreersDocpageItem,
            BaseItem,
            AcquaintanceListPageModal,
            RefuseDocumentModal,
            DocumentImageModal,
            InWorkAlert,
            UserFioLink,
            TimestampElement,
            Multiselect,
            NewAdditionTemplate,
            AdditionTemplate,
            AcquaintanceSendTemplate,
            DeleteAdditionModal,
            DeleteAdditionFileModal,
            EditAgreementUserListModal,
            SaveDocInfoModal,
            DatePicker,
            GetFromArchiveModal,
            EditDocfileTemplate,
            SaveDocFileModal,
            DocFileLink,
            AgreementListPreviewTemplate,
            DocAssignSelectAlert,
            DiruserAddTemplate,
            PreviewPDFRowElement,
            BackListLink,
            RelationsAddTemplate,
            RelationsListModal,
            MultiselectListTemplate,
            RelationItem,
            RelationsListTemplate,
            DeleteRelationModal,
        }
    },
    {
        path: '/newagreements',
        name: 'newagreements',
        components: {
            NewAgreementsList,
            AgreementsListItem,
            UserFioLink,
            TimestampElement,
            IndexTd,
        }
    },
    {
        path: '/history',
        name: 'history',
        components: {
            HistoryList,
            HistoryLogItem,
            UserFioLink,
            TimestampElement,
            ListPaginationTemplate,
            IndexTd,
        }
    },
    {
        path: '/blog',
        name: 'blog',
        components: {
            Blog,
            InWorkAlert,
            TimestampElement,
        }
    },
    {
        path: '/feedback',
        name: 'feedback',
        components: {
            Feedback,
            TimestampElement,
        }
    },
    {
        path: '/assignment-:id',
        name: 'assignment-page',
        components: {
            AssignmentPage,
            AssignmentPageExecutorItem,
            RefuseAssignmentModal,
            AssignStatusLogModal,
            PersonalControlModal,
            InWorkAlert,
            RefuseAssignmentDeadlineModal,
            DeadlinesLogModal,
            UserFioLink,
            TimestampElement,
            Multiselect,
            NewAdditionTemplate,
            AdditionTemplate,
            DeleteAdditionModal,
            DeleteAdditionFileModal,
            SaveAssignInfoModal,
            DocAssignSelectAlert,
            RelationsAddTemplate,
            RelationsListModal,
            MultiselectListTemplate,
            DeleteRelationModal,
        }
    },
    {
        path: '/letters',
        name: 'letters-list',
        components: {
            LettersList,
            LettersListItem,
            DocStatusTd,
            UserFioLink,
            TimestampElement,
            DirusersSelect,
            DateToggleElement,
            DocsbytypeTbody,
            ListPaginationTemplate,
            ChoosePaginationTemplate,
            IndexTd,
            NotFoundTr,
            DocsListThead,
            DocsbytypeTable,
            RelationsAddTemplate,
        }
    },
    {
        path: '/archivedocs',
        name: 'archivedocs-list',
        components: {
            ArchivedocsList,
            DocStatusTd,
            UserFioLink,
            TimestampElement,
            DateToggleElement,
            ListPaginationTemplate,
            ChoosePaginationTemplate,
            IndexTd,
            NotFoundTr,
            DocsListThead,
            RelationsAddTemplate,
        }
    },
    {
        path: '/acquaintances',
        name: 'acquaintances-list',
        components: {
            AcquaintancesList,
            AcquaintancesListItem,
            UserFioLink,
            TimestampElement,
            DateToggleElement,
            ListPaginationTemplate,
            IndexTd,
            RelationsAddTemplate,
        }
    },
    {
        path: '/account-:id',
        name: 'user-page',
        components: {
            UserPage,
            UserMailSettingsModal,
            InWorkAlert,
            UserFioLink,
            TimestampElement,
        }
    },
    {
        path: '/analytics',
        name: 'analytics-page',
        components: {
        //     UserPage,
        //     UserMailSettingsModal,
        //     InWorkAlert,
        //     UserFioLink,
            TimestampElement,
        }
    },
    {
        path: '/search',
        name: 'search',
        components: {
            SearchTemplate,
            SearchAssignmentItem,
            SearchAssignmentShortItem,
            SearchDocumentItem,
            SearchDocumentShortItem,
        }
    },
    {
        path: '/info',
        name: 'info',
        components: {
            InfoPage,
        }
    },
    {
        path: '/outletters',
        name: 'outletters-list',
        components: {
            OutlettersList,
            LettersListItem,
            DocStatusTd,
            UserFioLink,
            TimestampElement,
            DirusersSelect,
            DateToggleElement,
            DocsbytypeTbody,
            ListPaginationTemplate,
            ChoosePaginationTemplate,
            IndexTd,
            NotFoundTr,
            DocsListThead,
            DocsbytypeTable,
            RelationsAddTemplate,
        }
    },
    {
        path: '/contracts',
        name: 'contracts-list',
        components: {
            ContractsList,
            LettersListItem,
            DocStatusTd,
            UserFioLink,
            TimestampElement,
            DirusersSelect,
            DateToggleElement,
            DocsbytypeTbody,
            ListPaginationTemplate,
            ChoosePaginationTemplate,
            IndexTd,
            NotFoundTr,
            DocsListThead,
            DocsbytypeTable,
            RelationsAddTemplate,
        }
    },
    {
        path: '/ordersod',
        name: 'ordersod-list',
        components: {
            OrdersODList,
            LettersListItem,
            DocStatusTd,
            UserFioLink,
            TimestampElement,
            DirusersSelect,
            DateToggleElement,
            DocsbytypeTbody,
            ListPaginationTemplate,
            ChoosePaginationTemplate,
            IndexTd,
            NotFoundTr,
            DocsListThead,
            DocsbytypeTable,
            RelationsAddTemplate,
        }
    },
    {
        path: '/memos',
        name: 'memos-list',
        components: {
            MemosList,
            LettersListItem,
            DateToggleElement,
            DocsbytypeTbody,
            ListPaginationTemplate,
            ChoosePaginationTemplate,
            IndexTd,
            NotFoundTr,
            DocsListThead,
            DocsbytypeTable,
            RelationsAddTemplate,
        }
    },
    {
        path: '/otherdocs',
        name: 'otherdocs-list',
        components: {
            OtherDocsList,
            LettersListItem,
            DateToggleElement,
            DocsbytypeTbody,
            ListPaginationTemplate,
            ChoosePaginationTemplate,
            IndexTd,
            NotFoundTr,
            DocsListThead,
            DocsbytypeTable,
            RelationsAddTemplate,
        }
    },
    {
        path: '/notifications',
        name: 'notifications-list',
        components: {
            NotificationsList,
            LettersListItem,
            DateToggleElement,
            DocsbytypeTbody,
            ListPaginationTemplate,
            ChoosePaginationTemplate,
            IndexTd,
            NotFoundTr,
            DocsListThead,
            DocsbytypeTable,
            RelationsAddTemplate,
        }
    },
    {
        path: '/ordocs',
        name: 'ordocs-list',
        components: {
            ORDocsList,
            LettersListItem,
            DateToggleElement,
            DocsbytypeTbody,
            ListPaginationTemplate,
            ChoosePaginationTemplate,
            IndexTd,
            NotFoundTr,
            DocsListThead,
            DocsbytypeTable,
            RelationsAddTemplate,
        }
    },
    // AddAgreementsList
    {
        path: '/addagreements',
        name: 'addagreements-list',
        components: {
            AddAgreementsList,
            LettersListItem,
            DateToggleElement,
            DocsbytypeTbody,
            ListPaginationTemplate,
            ChoosePaginationTemplate,
            IndexTd,
            NotFoundTr,
            DocsListThead,
            DocsbytypeTable,
            RelationsAddTemplate,
        }
    },
    {
        path: '/socs',
        name: 'socs-list',
        components: {
            SOCsList,
            LettersListItem,
            DateToggleElement,
            DocsbytypeTbody,
            ListPaginationTemplate,
            ChoosePaginationTemplate,
            IndexTd,
            NotFoundTr,
            DocsListThead,
            DocsbytypeTable,
            RelationsAddTemplate,
        }
    },
]

const router = new VueRouter({ 
    routes,
    mode: 'history',
 })

const vm = new Vue({
    el: '#app',
    router: router,
    data() {
        return {
            storage: null,   
            spinOffSingle: true,
            spinOffLetters: true,
            lettersList: [],
            lettersListTotal: [],
            authorLength: 0,
            authorList: 0,
            selectedTitle: 1,
            directUsers: [],
            dateMode: 0,
            docsList: [],
            docsListAll: [],
            docsWithout: [],
            spinOffDocs: true,
            pagiCount: 1,
            pagiCountAll: 1,
            pagiCountEx: 1,
            currPagiPage: 1,
            currPagiPageAll: 1,
            currPagiPageEx: 1,
            docsListFirst: 1,
            docsListLast: 10,
            docsListFirstAll: 1,
            docsListLastAll: 10,
            pagiType: 'doc',
            assignsShortList: [],
            assignsShortListAll: [],
            assignsListExecutor: [],
            assignsList: [],
            assignsListAll: [],
            assignsListFirst: 1,
            assignsListLast: 10,
            assignsListFirstEx: 1,
            assignsListLastEx: 10,
            assignsListFirstAll: 1,
            assignsListLastAll: 10,
            orderMode: 'DESC',
            usersList: [],
            documentTypes: [],
            sortMode: false,
            sortArr: null,
            docsPanel: true,
            lettersPanel: true,
            docCount: 5,
            lettersCount: 5,
            dateModeLetter: true,
            disablePagi: false,
            relationsList: [],
            filteredRelDocs: [],
            filteredRelAssigns: [],
	        baseDocId: null,
	        baseAssignId: null,
	        baseRefusedDocId: null,
            filteredDocs: [],
            filteredAssigns: [],
            addTemplate: false,
            newAdd: false,
            openRels: false,
            ascsPanel: true,
            acqCount: 0,
            acquaintancesList: [],
            spinOffAcqs: true,
            roleData: {
                roleId: null,
            },
        }
    },
    // mounted() {
    //     this.docsAll();
    //     this.assignsAll();
    // },
    methods: {
		refreshPage: function() {
            this.$router.go();
        },
        makeFio: function(surname, firstname = null, patronymic = null, len = null) {
    		if (firstname == null) {
    			if (len != null) {
    				surname = (surname.length > len) ? surname.slice(0, len).trim() + '...' : surname;
    			}
                return surname;
            } else {
                if (patronymic == null) {
                    return surname + ' ' + firstname.slice(0, 1).toUpperCase() + '.';
                } else {
                    return surname + ' ' + firstname.slice(0, 1).toUpperCase() + '. ' + patronymic.slice(0, 1).toUpperCase() + '.';
                }
            }
        },
        addInArchive: function(id, statusId) {
            axios.post('api/docintoarchive', {id: id, statusId: statusId}, {
                headers: {
                    "Content-Type": "application/json"
                }
            })
            .then(response => {
                if (response.data.error == 0) {
                    console.log(response.data.result.id);
                    this.$router.go();
                } else {
                    console.log(response.data);
                }
            }).catch(error => {
                alert('Ошибка получения данных');
                this.userMessage = 2;
                console.log(error);
            });
        },
        docLink: function(id) {
            // window.location.href = '/document-'+id;
            window.open('/document-' + id, '_blank')
        },
        assignLink: function(id, assignExecutorId = null) {
            if (assignExecutorId != null) {
                this.makeViewedAssignment(assignExecutorId);
            };
            // window.location.href = '/assignment-' + id;
            window.open('/assignment-' + id, '_blank')
        },
        makeViewedAgreement: function(id) {
            axios.post('api/updateagreement', {id: id, viewed: 1}, {
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                .then(response => {
                    if (response.data.error == 0) {
                        if (response.data.result != null) {
                            console.log('просмотрено');
                        } else {
                            alert('Ошибка отправки данных');
                        }
                    } else {
                        alert(response.data.error_message);
                    }
                }).catch(error => {
                    alert('Ошибка получения данных');
                    console.log(error);
                });
        },
        makeViewedAssignment: function(id) {
            axios.post('api/updateassignment', {id: id, viewed: 1}, {
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                .then(response => {
                    if (response.data.error == 0) {
                        if (response.data.result != null) {
                            console.log('просмотрено');
                        } else {
                            alert('Ошибка отправки данных');
                        }
                    } else {
                        alert(response.data.error_message);
                    }
                }).catch(error => {
                    alert('Ошибка получения данных');
                    console.log(error);
                });
        },
        getListByDocTypeId: function(typeId, userId, mode = false, count = null, start = false) {
            this.spinOffLetters = true;
            if (start == true) {
                console.log('wefwef');
                this.lettersPanel = (this.lettersPanel === false) ? true : false;
            };
            let data = {
                typeId: typeId, 
                creationDate: null, 
                ascDesc: this.orderMode,
            };
            if (count !== null) {
                data.count = count;
            }
            data.creationDate = (mode === true) ? 1 : null;
            axios.post('api/getdocslistbytype', data, {
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                .then(response => {
                    if (response.data.error == 0) {
                        this.docsListAll = response.data.result;
                        this.lettersList = this.docsListAll;
                        if (count !== null) {
                            this.lettersCount = count;
                            console.log(this.lettersList);
                            this.spinOffLetters = false;
                        } else {
                            this.spinOffSingle = false;
                            this.pagiCountAll = this.countPages(this.docsListAll);
                            console.log(this.pagiCountAll);
                            this.getDocsRange(this.docsListAll, this.currPagiPageAll, this.pagiCountAll, true);
                            this.docsList = this.filterByAuthorId(this.docsListAll, userId);
                            this.pagiCount = this.countPages(this.docsList);
                            this.getDocsRange(this.docsList, this.currPagiPage, this.pagiCount, false);
                            // console.log('Кол-во страниц: '+ this.pagiCount);
                        }
                    } else {
                        alert(response.data.error_message);
                    }
                }).catch(error => {
                    alert('Ошибка получения данных');
                    console.log(error);
                });
            if (this.selectedTitle == 1) {
                this.makeParamsPage(this.currPagiPage);
            } else if (this.selectedTitle == 2) {
                this.makeParamsPage(this.currPagiPageAll);
            };
        },
        toggleTitle: function(n) {
            if (n == 1) {
                this.selectedTitle = n;
                console.log(this.currPagiPage);
                this.getDocsRange(this.docsList, this.currPagiPage, this.pagiCount, false);
                this.routerPush({
                    type: n,
                    page: this.currPagiPage,
                });
            } else if (n == 2) {
                this.selectedTitle = n;
                console.log(this.currPagiPageAll);
                this.getDocsRange(this.docsListAll, this.currPagiPageAll, this.pagiCountAll, true);
                this.routerPush({
                    type: n,
                    page: this.currPagiPageAll,
                });
            };
        },
        checkDate: function(date, now) {
            let dt = new Date(date);
            let delta = (dt - now)/(24*3600*1000);
            if ((dt - now) < 0) {
                return 0;
            } else {
                if (delta <= 3) {
                    return 1;
                } else {
                    return 2;
                }
            }
        },
        getDocsByDiruserId: function(diruserId, userId = null) {
            if (diruserId == null) {
                this.docsListAll = this.lettersList;
            } else {
                if (this.lettersList.length !== 0) {
                    this.docsListAll = [];
                    this.lettersList.forEach(item => {
                        if (item.diruser != false) {
                            if (item.diruser.diruserId == diruserId) {
                                this.docsListAll.push(item)
                            };
                        };
                    });
                };
            };
            if (userId !== null) {
                this.docsList = this.filterByAuthorId(this.docsListAll, userId);
            }
        },
        filterByAuthorId: function(arr, userId) {
            var filter = [];
            // console.log(arr);
            arr.forEach(item => {
                if (item.authorId == userId) {
                    filter.push(item);
                }
            });
            this.pagiCount = this.countPages(this.docsList);
            this.getDocsRange(this.docsList, this.currPagiPage, this.pagiCount, false);
            return filter;
        },
        namesFull({ surname, firstname, patronymic }) {
            surname = (surname != null) ? surname : '';
            firstname = (firstname != null) ? firstname : '';
            patronymic = (patronymic != null) ? patronymic : '';
            return `${surname} ${firstname} ${patronymic}`;
        },
        getDirusers: function() {
            axios.post('api/diruserslist', {
                headers: {
                    "Content-Type": "application/json"
                }
            })
            .then(response => {
                if (response.data.error == 0) {
                    this.directUsers = response.data.result;
                } else {
                    alert(response.data.error_message);
                }
            }).catch(error => {
                alert('Ошибка получения данных');
                console.log(error);
            });
        },
        frmtDate: function(dateTime, short = false) {
            // return date.split('.')[0];
            // console.log(this.dateTime);
            if (this.ios() == true) {
                dateTime = dateTime.replace(" ", "T");
            }
            var date = new Date(dateTime);
            var tzone = date.getTimezoneOffset()*(-1);
            date.setHours(date.getHours() + tzone/60);
            var day = (date.getDate() < 10) ? ('0' + date.getDate()) : date.getDate();
            var month = (date.getMonth() < 9) ? ('0' + (date.getMonth() + 1)) : (date.getMonth() + 1);
            var hour = (date.getHours() < 10) ? ('0' + date.getHours()) : date.getHours();
            var min = (date.getMinutes() < 10) ? ('0' + date.getMinutes()) : date.getMinutes();
            var sec = (date.getSeconds() < 10) ? ('0' + date.getSeconds()) : date.getSeconds();
            if (short === true) {
                var dt = day + '.' + month + '.' + date.getFullYear();
            } else {
                var dt = day + '.' + month + '.' + date.getFullYear() + ' ' + hour + ':' + min + ':' + sec;
            }
            
            return dt;
        },
        ios: function() {
            return [
                'iPad Simulator',
                'iPhone Simulator',
                'iPod Simulator',
                'iPad',
                'iPhone',
                'iPod',
                'Mac68K',
                'MacPPC',
                'MacIntel'
                ].includes(navigator.platform)
                // iPad on iOS 13 detection
                || (navigator.userAgent.includes("Mac") && "ontouchend" in document)
        },
        frmtDateIn: function(d, t = null) {
            console.log(d);
            if (d != null) {
                return this.dateTimeFrmt(d, t);
            } else {
                return null;
            }
        },
        frmtDateRangeIn: function(arr) {
            let newArr = [];
            arr.forEach(item => {
                if (item != null) {
                    newArr.push(this.dateTimeFrmt(item));
                } else {
                    newArr.push(null);
                }
            });
            return newArr;
        },
        dateTimeFrmt: function(d, t = null) {
            let dt = d.split(' ')[0];
            let time;
            time = (t != null) ? t : '00:00:00';
            let dateTime = dt.split('.')[2] +'-'+ dt.split('.')[1] +'-'+ dt.split('.')[0] + ' ' + time;
            let date = new Date(dateTime);
            if (t != null) {
                let tzone = date.getTimezoneOffset();
                date.setHours(date.getHours() + tzone/60);
            }
            // console.log(tzone);
            let day = (date.getDate() < 10) ? ('0' + date.getDate()) : date.getDate();
            let month = (date.getMonth() < 9) ? ('0' + (date.getMonth() + 1)) : (date.getMonth() + 1);
            let hour = (date.getHours() < 10) ? ('0' + date.getHours()) : date.getHours();
            let min = (date.getMinutes() < 10) ? ('0' + date.getMinutes()) : date.getMinutes();
            let sec = (date.getSeconds() < 10) ? ('0' + date.getSeconds()) : date.getSeconds();
            let total = date.getFullYear() +'-'+ month +'-'+ day +' '+ hour +':'+ min +':'+ sec;
            return total;
        },
        toggleDateMode: function(userId) {
            this.dateMode = (this.dateMode === 0) ? 1 : 0;
            // this.orderMode = "DESC";
            console.log(this.orderMode);
        },
        toggleDate: function(userId = null, typeId = null) {
            console.log('wdfq');
            if (typeId !== null) {
                if (this.selectedTitle == 1) {
                    if (this.dateMode == 0) {
                        this.spinOffSingle = true;
                        this.getListByDocTypeId(typeId, userId, true);
                    } else if (this.dateMode == 1) {
                        this.spinOffSingle = true;
                        this.getListByDocTypeId(typeId, userId, false);
                    };
                } else if(this.selectedTitle == 2) {
                    if (this.dateMode == 0) {
                        this.spinOffSingle = true;
                        this.getListByDocTypeId(typeId, userId, true);
                    } else if (this.dateMode == 1) {
                        this.spinOffSingle = true;
                        this.getListByDocTypeId(typeId, userId, false);
                    };
                };
            } else {
                if (this.selectedTitle == 1) {
                    if (this.dateMode == 0) {
                        this.spinOffDocs = true;
                        this.getDocs(userId, null, true);
                    } else if (this.dateMode == 1) {
                        this.spinOffDocs = true;
                        this.getDocs(userId, null, false);
                    }
                } else if (this.selectedTitle == 2) {
                    if (this.dateMode == 0) {
                        this.spinOffDocs = true;
                        this.docsAll(true);
                    } else if (this.dateMode == 1) {
                        this.spinOffDocs = true;
                        this.docsAll(false);
                    }
                }
            }
        },
        routerPush: function(arr) {
            this.$router.push({
                name: this.$route.name, 
                query: { 
                    type: arr.type, 
                    page: arr.page,
                }
            }).catch(err => {});
        },
        checkGetPages: function(total, curr) {
            if (Number(curr) > Number(total)) {
                console.log(total);
                return total;
            } else if (Number(curr) < 1) {
                return 1;
            };
            return curr;
        },
        getDocsRange: function(arr, page, count, all = true) {
            // console.log("страница - " + page + ', количество страниц - '+ count);
            if (page == count) {
                if (all == true) {
                    this.docsListFirstAll = page*10 - 9;
                    this.docsListLastAll = arr.length;
                } else {
                    this.docsListFirst = page*10 - 9;
                    this.docsListLast = arr.length;
                };
            } else {
                if (all == true) {
                    this.docsListFirstAll = page*10 - 9;
                    this.docsListLastAll = page*10;
                } else {
                    this.docsListFirst = page*10 - 9;
                    this.docsListLast = page*10;
                };
            };
            // console.log('с '+ this.docsListFirst + ' до ' + this.docsListLast);
        },
        getAssignsRange: function(arr, page, count, type = null) {
            console.log("страница - " + page + ', количество страниц - '+ count);
            if (page == count) {
                if (type == null) {
                    this.assignsListFirst = page*10 - 9;
                    this.assignsListLast = arr.length;
                    console.log('Null с '+ this.assignsListFirst + ' до ' + this.assignsListLast);
                } else if (type == 'ex') {
                    this.assignsListFirstEx = page*10 - 9;
                    this.assignsListLastEx = arr.length;
                    console.log('Ex с '+ this.assignsListFirstEx + ' до ' + this.assignsListLastEx);
                } else if (type == 'all') {
                    this.assignsListFirstAll = page*10 - 9;
                    this.assignsListLastAll = arr.length;
                    console.log('All с '+ this.assignsListFirstAll + ' до ' + this.assignsListLastAll);
                };
            } else {
                if (type == null) {
                    this.assignsListFirst = page*10 - 9;
                    this.assignsListLast = page*10;
                    console.log('Null с '+ this.assignsListFirst + ' до ' + this.assignsListLast);
                } else if (type == 'ex') {
                    this.assignsListFirstEx = page*10 - 9;
                    this.assignsListLastEx = page*10;
                    console.log('Ex с '+ this.assignsListFirstEx + ' до ' + this.assignsListLastEx);
                } else if (type == 'all') {
                    this.assignsListFirstAll = page*10 - 9;
                    this.assignsListLastAll = page*10;
                    console.log('All с '+ this.assignsListFirstAll + ' до ' + this.assignsListLastAll);
                };
            };
        },
        makeParamsPage: function(n = 1) {
            if (this.$route.query.type || this.$route.query.page) {
                if (this.$route.query.type) {
                    this.selectedTitle = this.$route.query.type;
                };
                if (this.$route.query.page) {
                    if (this.selectedTitle == 1) {
                        this.currPagiPage = this.$route.query.page;
                    } else if (this.selectedTitle == 2) {
                        this.currPagiPageAll = this.$route.query.page;
                    }
                };
            } else {
                this.routerPush({
                    type: this.selectedTitle,
                    page: n,
                });
            };
        },
        makeParamsPageAssign: function(n = 1) {
            if (this.$route.query.type || this.$route.query.page) {
                if (this.$route.query.type) {
                    this.selectedTitle = this.$route.query.type;
                };
                if (this.$route.query.page) {
                    if (this.selectedTitle == 1) {
                        this.currPagiPage = this.$route.query.page;
                    } else if (this.selectedTitle == 2) {
                        this.currPagiPageEx = this.$route.query.page;
                    } else if (this.selectedTitle == 3) {
                        this.currPagiPageAll = this.$route.query.page;
                    };
                };
            } else {
                this.routerPush({
                    type: this.selectedTitle,
                    page: n,
                });
            };
        },
        countPages: function(arr) {
            if (this.selectedTitle == 1) {
                this.makeParamsPage(this.currPagiPage);
            } else if (this.selectedTitle == 2) {
                this.makeParamsPage(this.currPagiPageAll);
            };
            return Math.floor(arr.length / 10) + 1;
        },
        countPagesAssign: function(arr) {
            if (this.selectedTitle == 1) {
                this.makeParamsPageAssign(this.currPagiPage);
            } else if (this.selectedTitle == 2) {
                this.makeParamsPageAssign(this.currPagiPageEx);   
            } else if (this.selectedTitle == 3) {
                this.makeParamsPageAssign(this.currPagiPageAll);
            };
            return Math.floor(arr.length / 10) + 1;
        },
        changePagiPage: function(n) {
        	window.scrollTo({
				top: 0,
				behavior: "smooth",
			});
            if (this.pagiType === 'doc') {
                if (this.selectedTitle == 1) {
                    this.currPagiPage = n;
                    this.getDocsRange(this.docsList, this.currPagiPage, this.pagiCount, false);
                } else if (this.selectedTitle == 2) {
                    this.currPagiPageAll = n;
                    this.getDocsRange(this.docsListAll, this.currPagiPageAll, this.pagiCountAll, true);
                };
            } else if (this.pagiType === 'assign') {
                if (this.selectedTitle == 1) {
                    this.currPagiPage = n;
                    this.getAssignsRange(this.assignsShortList, this.currPagiPage, this.pagiCount, null);
                } else if (this.selectedTitle == 2) {
                    console.log(this.currPagiPageEx);
                    this.currPagiPageEx = n;
                    this.getAssignsRange(this.assignsListExecutor, this.currPagiPageEx, this.pagiCountEx, 'ex');
                } else if (this.selectedTitle == 3) {
                    this.currPagiPageAll = n;
                    this.getAssignsRange(this.assignsShortListAll, this.currPagiPageAll, this.pagiCountAll, 'all');
                };
            };
            this.routerPush({
                type: this.selectedTitle,
                page: n,
            });
        },
        toggleOrder: function(typeId = null, userId = null) {
            this.orderMode = (this.orderMode === 'DESC') ? 'ASC' : 'DESC';
            if (typeId !== null) {
                if (this.dateMode == 0) {
                    this.spinOffSingle = true;
                    this.getListByDocTypeId(typeId, userId, true);
                } else if (this.dateMode == 1) {
                    this.spinOffSingle = true;
                    this.getListByDocTypeId(typeId, userId, false);
                };
            } else {
                this.toggleDate(userId);
            }
        },
        getUsersList: function(userId) {
            axios.post('api/getuserslist', {id: userId}, {
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                .then(response => {
                    if (response.data.error == 0) {
                        // console.log(response.data.result);
                        this.usersList = response.data.result;
                    } else {
                        alert(response.data.error_message);
                    }
                }).catch(error => {
                    alert('Ошибка получения данных');
                    console.log(error);
                });
        },
        indexUpd: function(n) {
            console.log('ewf');
            return n + 1;
        },
        getDocumentTypes: function() {
            axios.post('api/getdocumenttypes')
                .then(response => {
                    if (response.data.error == 0) {
                        this.documentTypes = response.data.result;
                    } else {
                        alert(response.data.error_message);
                    }
                }).catch(error => {
                    alert('Ошибка получения данных');
                    console.log(error);
                });
        },
        getDocs: function(userId, count = null, mode = false, start = false) {
        	this.spinOffDocs = true;
        	let data = {
				creationDate: null,
				authorId: userId,
				ascDesc: this.orderMode,
			};
			if (start == true) {
				this.docsPanel = (this.docsPanel === false) ? true : false;
			};
			data.creationDate = (mode === true) ? 1 : null;
			axios.post('api/getdocslistbyuser', data, {
				headers: {
					"Content-Type": "application/json"
				}
			})
				.then(response => {
					let list = [];
					this.docsList = [];
					if (response.data.error == 0) {
						this.spinOffDocs = false;
						list = response.data.result;
						if (count !== null) {
							this.docCount = count;
							if (list.length > 0) {
								count = (count > list.length) ? list.length : count;
								for (let i = 0; i < count; i++) {
									if (list[i].status.docstatusId != 4) {
										this.docsList.push(list[i]);
									}
								};
							}
						} else {
							this.docsList = list;
							this.pagiCount = this.countPages(this.docsList);
							this.getDocsRange(this.docsList, this.currPagiPage, this.pagiCount);
						}
						console.log(this.docsList);
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
		},
		docsAll: function(mode = false) {
			let data = {
				creationDate: null,
				ascDesc: this.orderMode,
			};
			data.creationDate = (mode === true) ? 1 : null;
			axios.post('api/getdocslist', data, {
				headers: {
					"Content-Type": "application/json"
				}
			})
				.then(response => {
					if (response.data.error == 0) {
						this.spinOffDocs = false;
						this.docsListAll = response.data.result;
						this.pagiCountAll = this.countPages(this.docsListAll);
						console.log(this.docsListAll);
						this.getDocsRange(this.docsListAll, this.currPagiPageAll, this.pagiCountAll);
						// убрать для оптимизации
						this.docsWithout = [];
						this.docsListAll.forEach(item => {
							if (item.status.docstatusId === 2) {
								this.docsWithout.push(item);
							}
						});
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
		},
        docsAllRet: function(mode = false) {
            let data = {
                creationDate: null,
                ascDesc: this.orderMode,
            };
            let docs = [];
            data.creationDate = (mode === true) ? 1 : null;
            axios.post('api/getdocslist', data, {
                headers: {
                    "Content-Type": "application/json"
                }
            })
                .then(response => {
                    if (response.data.error == 0) {
                        this.spinOffDocs = false;
                        docs = response.data.result;
                        this.pagiCountAll = this.countPages(docs);
                        console.log(docs);
                        this.getDocsRange(docs, this.currPagiPageAll, this.pagiCountAll);
                        // убрать для оптимизации
                        // this.docsWithout = [];
                        // this.docsListAll.forEach(item => {
                        //     if (item.status.docstatusId === 2) {
                        //         this.docsWithout.push(item);
                        //     }
                        // });
                        // console.log('cccccooodc');
                        return docs;
                    } else {
                        alert(response.data.error_message);
                    }
                }).catch(error => {
                    alert('Ошибка получения данных');
                    console.log(error);
                });
        },
		getRelations: function(id = null, type = null) {
			let data = {};
			if (id !== null) {
				if (type === 'doc') {
					data = {
						documentId1: id,
					};
				} else if (type === 'assign') {
					data = {
						assignmentId1: id,
					};
				}
			}
			axios.post('api/getrelations', data, {
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                .then(response => {
                    if (response.data.error == 0) {
                        let result = response.data.result;
                        this.relationsList = this.recuRelTotal(result);
                        this.filterRelations(id, type);
                    } else {
                        alert(response.data.error_message);
                    }
                }).catch(error => {
                    alert('Ошибка получения данных');
                    console.log(error);
                });
		},
        // исправить
        recuRelTotal: function(arr, oldArr = null, id = null) {
            let newArr = [];
            if (oldArr == null) {
                arr.forEach(item => {
                    if (item.mainId == null) {
                        newArr.push(item);
                    }
                });
            } else {
                newArr = arr;
            }
            newArr.forEach(item => {
                let sub = [];
                arr.forEach(value => { 
                    if (id == null) {
                        if (value.mainId != null) {
                            if (value.mainId === item.id) {
                                sub.push(value);
                            }
                        }
                    } else {
                        if (value.mainId != null) {
                            if (value.mainId === id) {
                                sub.push(value);
                            }
                            item.sub = this.recuRelTotal(sub, arr, item.id);
                        } else {
                            item.sub = null;
                        }
                        // здесь попробовать добавить подмножество
                    }
                    item.sub = sub;
                });
            });
            return newArr;
        },
        openRelsFunc: function(mode, id = null, type = 'doc') {
            this.openRels = mode;
            if (id != null) {
                this.filterRelations(id, type);
            }
            // console.log(this.filteredDocs);
            window.scrollTo({
                top: 3000,
                behavior: "smooth",
            });
        },
        filterRelations: function(id, type = 'doc') {
            this.docsAll(true);
            this.assignsAll();
            // this.openRels = (this.openRels == false) ? true : false;
            // let antiDocs = [];
            // let antiAssigns = [];
            if ((this.docsListAll.length > 0) && (this.assignsListAll.length > 0)) {
                if (type === 'doc') {
                    this.docsListAll.forEach(value => {
                        if (this.relationsList.findIndex(item => item.documentId2 == value.id || item.documentId1 == value.id) === -1) {
                            if (value.id != id) {
                                this.filteredDocs.push(value);
                            }
                        }
                    });
                    this.assignsListAll.forEach(value => {
                        if (this.relationsList.findIndex(item => item.assignmentId2 == value.id || item.assignmentId1 == value.id) === -1) {
                            this.filteredAssigns.push(value);
                        }       
                    });
                } else if (type === 'assign') {
                    this.docsListAll.forEach(value => {
                        if (this.relationsList.findIndex(item => item.documentId2 == value.id || item.documentId1 == value.id) === -1) {
                            this.filteredDocs.push(value);
                        }
                    });
                    this.assignsListAll.forEach(value => {
                        if (this.relationsList.findIndex(item => item.assignmentId2 ==  value.id || item.assignmentId1 == value.id) === -1) {
                            if (value.id != id) {
                                this.filteredAssigns.push(value);
                            }
                        }
                    });
                }
            }
        },
        getMultiple: function(arr) {
            let list = [];
            for (let i = 0; i < arr.length; i++) {
                if (arr[i].mainId != null) {
                    if ((i > 0) && (arr[i].mainId == arr[i - 1].mainId)) {
                        let item = list.find(item => item.main === arr[i].mainId);
                        item.arr.push(arr[i]);
                    } else {
                        list.push({
                            main: arr[i].mainId,
        	                    arr: [arr[i]],
                        });
                    }
                } else {
                    list.push(arr[i]);
                };
            };
            return list;
        },
        assignsAll: function() {
            axios.post('api/getallassignments', {
                headers: {
                    "Content-Type": "application/json"
                }
            })
                .then(response => {
                    if (response.data.error == 0) {
                        this.assignsListAll = response.data.result;
                        this.spinOffSingle = false;
                        this.assignsShortListAll = this.getMultiple(this.assignsListAll);
                        this.pagiCountAll = this.countPagesAssign(this.assignsShortListAll);
                        this.getAssignsRange(this.assignsShortListAll, this.currPagiPageAll, this.pagiCountAll, 'all');
                    } else {
                        alert(response.data.error_message);
                    }
                }).catch(error => {
                    alert('Ошибка получения данных');
                    console.log(error);
                });
        },
        expectIds: function(arr, id) {
        	if (arr.assignmentId2 == id) {
        		console.log(arr.assignmentId2);
        		return -1;
        	}
        },
		baseDocTitle: function({ description, created_at, author, id, orderNum = null, creationDate = null, outerNum = null }) {
			if (orderNum != null || creationDate != null || outerNum != null) {
				let ordr = (orderNum != null) ? 'Док-т № '+ orderNum : '';
				let crdate = (creationDate != null) ? ' от ' + this.$root.frmtDate(creationDate, true) : '';
                let outer = (outerNum != null) ? ' (внеш. № '+ outerNum + ')' : '';
				return ordr + crdate +' '+ outer +' '+ description;
			} else {
				// return description +' (дата и время внесения: '+ this.$root.frmtDate(created_at) + ') (' + this.$root.makeFio(author.surname, author.firstname, author.patronymic) +'), карточка док-та № ' + id;
                return description +' (дата и время внесения: '+ this.$root.frmtDate(created_at) + ') , карточка док-та № ' + id;
			}
		},
		baseAssignTitle: function({ text, created_at, author, id }) {
			return text + ' от '+ created_at.split(' ')[0] +' (поручение № ' + id + ')';
		},
        getAcquaintances: function(userId, notViewed = null, start = false) {
            if (start == true) {
                this.ascsPanel = (this.ascsPanel === false) ? true : false;
            };
            if (this.ascsPanel == true) {
                this.acqCount = 0;
                let data = (notViewed === 1) ? {userId: userId, notViewed: 1} : {userId: userId};
                axios.post('api/acquaintanceslist', data)
                    .then(response => {
                        if (response.data.error == 0) {
                            this.acquaintancesList = response.data.result;
                            this.acquaintancesList.forEach(item => {
                                if (item.seen_at === null) {
                                    this.acqCount++;
                                }
                            });
                            this.spinOffAcqs = false;
                        } else {
                            // alert(response.data.error_message);
                        }
                    }).catch(error => {
                        alert('Ошибка получения данных');
                        console.log(error);
                    });
            };
        },
        getListByDocStatusId: function(statusId) {
            this.spinOffLetters = true;
            axios.post('api/docslistbystatus', {statusId: statusId}, {
                headers: {
                    "Content-Type": "application/json"
                }
            })
                .then(response => {
                    if (response.data.error == 0) {
                        this.docsListAll = response.data.result;
                        this.pagiCountAll = this.countPages(this.docsListAll);
                        console.log(this.pagiCountAll);
                        this.getDocsRange(this.docsListAll, this.currPagiPageAll, this.pagiCountAll, true);
                        this.spinOffSingle = false;
                    } else {
                        alert(response.data.error_message);
                    }
                }).catch(error => {
                    alert('Ошибка получения данных');
                    console.log(error);
                });
        },
        checkRole: function(userId) {
            axios.post('api/checkrole', {
                    userId: userId,
                    last: 1,
                }, {
                headers: {
                    "Content-Type": "application/json"
                }
            })
                .then(response => {
                    if (response.data.error == 0) {
                        this.roleData = response.data.result;
                        if (response.data.result == false) {
                            this.roleData = {
                                roleId: 2,
                                role: {
                                    id: 2, 
                                    title: "Пользователь (огр.)",
                                    slug: "USER",
                                },
                            };
                        }
                    } else {
                        alert(response.data.error_message);
                    }
                }).catch(error => {
                    alert('Ошибка получения данных');
                    console.log(error);
                });
        },
	},
});
