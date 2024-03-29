<?php
class ArtCentresPage extends Page {
  static $allowed_children = array("ArtCentresRegionPage");

  private static $db = array(
    'TitleTextFormatted' => 'Text'
  );

  private static $has_many = array(
  );

  private static $has_one = array(
  );

  function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.Main', new TextareaField('TitleTextFormatted', 'Formatted Title'), 'Content');

    return $fields;
  }

}
class ArtCentresPage_Controller extends Page_Controller {
  private static $allowed_actions = array (
  );

  public function init() {
    parent::init();

    $this->HomePage = DataObject::get_one("HomePage");
  }
}
