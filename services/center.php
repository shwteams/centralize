<?php
/**
Dans ce fichier il faut ajouter la liste des fichiers du composant/com_
et lancer leurs methode
*/

include_once '../composant/com_user/user.php';
include_once '../composant/com_etats/etats.php';
include_once '../composant/com_autre_observation/autreobservation.php';
include_once '../composant/com_partner/partner.php';
include_once '../composant/com_plugged/plugged.php';
include_once '../composant/com_reinsurer/reinsurer.php';
include_once '../composant/com_intermediate/intermediate.php';
include_once '../composant/com_customer/customer.php';
include_once '../composant/com_product/product.php';
include_once '../composant/com_contract/contract.php';
include_once '../composant/com_sinister/sinister.php';
include_once '../composant/com_sinistre_traiter/sinisterTreate.php';
include_once '../composant/com_dashbord/dashbord.php';
include_once '../composant/com_production/production.php';
include_once '../composant/com_collection/collection.php';
include_once '../composant/com_not_collected_production/notCollectedProduction.php';
include_once '../composant/com_collected_production/collectedProduction.php';
include_once '../composant/com_collected_not_production/collectedNotProduction.php';
include_once '../composant/com_receipt/receipt.php';
include_once '../composant/com_company_contract/companyContract.php';
include_once '../composant/com_reassur/reassur.php';
include_once '../composant/com_IAintegration/IAintegration.php';

if (isset($_GET["task"])) {
    $task = $_GET["task"];
}
switch ($task) {
    case 'showReassur':
        echo showReassur();
        break;
    case 'showAllReceip':
        echo showAllReceip();
        break;
    case 'showCompagnyContract':
        echo showCompagnyContract();
        break;
    case 'showAllCollectedNotProduct':
        echo showAllCollectedNotProduct();
        break;
    case 'showCollection':
        echo showCollection();
        break;
    case 'showAllCollectedProd':
        echo showAllCollectedProd();
        break;
    case 'showListeOfSinistre':
        echo showListeOfSinistre();
        break;
    case 'showSinisterTreat':
        echo showSinisterTreat();
        break;
    case 'showProduct':
        echo showProduct();
        break;
    case 'showContract':
        echo showContract();
        break;
    case 'showCustomer':
        echo showCustomer();
        break;
    case 'showReinsurer':
        echo showReinsurer();
        break;
    case 'showIntermediate':
        echo showIntermediate();
        break;
    case 'showPlugged':
        echo showPlugged();
        break;
    case 'showPartner':
        echo showPartner();
        break;
    case 'showAutherObservation':
        echo showAutherObservation();
        break;
    case 'showEtats':
        echo showEtats();
        break;
    case 'showPhase':
        echo showPhase();
        break;
    case 'showUser':
        echo showUser();
        break;
    case 'showProduction':
        echo showProduction();
        break;
    case 'showAllNotCollectedProd':
        echo showAllNotCollectedProd();
        break;
    case 'showIAintegration':
        echo showIAintegration();
        break;
    default:
        echo showHomeAdminPage();
        //echo "Dashbord";
        break;
}
function showIAintegration()
{
    IAintegration::showIAintegration();
}
function showCompagnyContract()
{
    CompagnyContract::showAllCompagnyContract();
}
function showAllReceip()
{
    Receipt::showAllReceipt();
}
function showSinisterTreat()
{
    SinisterTreate::showAllSinisterTreate();
}
function showCustomer()
{
    Customer::showAllCustomer();
}
function showProduct()
{
    Product::showAllProduct();
}
function showIntermediate()
{
    Intermediate::showAllIntermediate();
}
function showReinsurer()
{
    Reinsurer::showAllReinsurer();
}
function showPlugged()
{
    Plugged::showAllPlugged();
}
function showPartner(){
    Partner::showAllPartner();
}
function showAutherObservation(){
    AutreObservation::showAutherObservation();
}
function showHomeAdminPage(){
    Dashbord::showHomeAdminPage();
}
function showUser(){
    User::showAllUser();
}
function showEtats(){
    Etats::showAllEtats();
}
function showContract(){
    Contract::showAllContract();
}
function showListeOfSinistre(){
    Sinister::showAllSinister();
}
function showProduction(){
    Production::showAllProduction();
}
function showCollection(){
    Collection::showAllCollection();
}
function showAllNotCollectedProd(){
    NotCollectedProduction::showAllNotCollectedProduction();
}
function showAllCollectedProd(){
    CollectedProduction::showAllCollectedProduction();
}
function showAllCollectedNotProduct(){
    CollectedNotProduction::showAllCollectedNotProduction();
}
function showReassur(){
    Reassur::showAllReassur();
}