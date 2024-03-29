<?php
class HomePage extends Page {

  private static $db = array(
    'HeroTextFormatted' => 'Text',
    'HeroTextSmallFormatted' => 'Text',
    'MsgTextFormatted' => 'Text',
    'WhoContent' => 'HTMLText',
    'WhoImageCredit' => 'Text',
    'HowContent' => 'HTMLText',
    'HowImageCredit' => 'Text',
    'KeepUpText' => 'HTMLText',
    'KeepUpImageCredit' => 'Text',
    'ImpactImageCredit' => 'Text',
    'FeatureImageCredit' => 'Text',
    'Project1ImageCredit' => 'Text',
    'Project2ImageCredit' => 'Text',
    'Project1Content' => 'HTMLText',
    'Project2Content' => 'HTMLText',
    'SocialIGLink' => 'Text',
    'SocialFBLink' => 'Text',
    'SocialVimeoLink' => 'Text'
  );

  private static $has_many = array(
    'SupporterElements' => 'SupporterElement'
  );

  private static $has_one = array(
    'HeroVideoFile' => 'File', 
    'WhoImage' => 'Image',
    'HowImage' => 'Image',
    'KeepUpImage' => 'Image',
    'ImpactImage' => 'Image',
    'FeatureImage' => 'Image',
    'Project1' => 'SiteTree',
    'Project2' => 'SiteTree',
    'Project1Image' => 'Image',
    'Project2Image' => 'Image'
  );

  function getCMSFields() {
    $fields = parent::getCMSFields();

    // remove fields
    $fields->removeFieldFromTab('Root.Main', 'Content');

    $fields->addFieldToTab('Root.Main', new LiteralField ('literalfield', '<strong>Hero</strong>'));
    $fields->addFieldToTab('Root.Main', new TextareaField('HeroTextFormatted', 'Text'));
    $fields->addFieldToTab('Root.Main', new TextareaField('HeroTextSmallFormatted', 'Text (Mobile)'));
    
    $fields->addFieldToTab('Root.Main', new LiteralField ('literalfield', '<strong>Message</strong>'));
    $fields->addFieldToTab('Root.Main', new TextareaField('MsgTextFormatted', 'Text'));

    // who we are
    $fields->addFieldToTab('Root.WhoWeAre', new HtmlEditorField('WhoContent', 'Text'));

    $uploadField1 = new UploadField($name = 'WhoImage', $title = 'Image');
    $uploadField1->setCanUpload(false);
    $fields->addFieldToTab('Root.WhoWeAre', $uploadField1);
    $fields->addFieldToTab('Root.WhoWeAre', new TextField('WhoImageCredit', 'Credit'));

    // how
    $fields->addFieldToTab('Root.WhatWeDo', new HtmlEditorField('HowContent', 'Text'));

    $uploadField2 = new UploadField($name = 'HowImage', $title = 'Image');
    $uploadField2->setCanUpload(false);
    $fields->addFieldToTab('Root.WhatWeDo', $uploadField2);
    $fields->addFieldToTab('Root.WhatWeDo', new TextField('HowImageCredit', 'Credit'));

    // feature
    $uploadField5 = new UploadField($name = 'FeatureImage', $title = 'Image');
    $uploadField5->setCanUpload(false);
    $fields->addFieldToTab('Root.Feature', $uploadField5);
    $fields->addFieldToTab('Root.Feature', new TextField('FeatureImageCredit', 'Credit'));

    // projects
    $fields->addFieldToTab('Root.Projects', new LiteralField ('literalfield', '<strong>Project 1</strong>'));
    $fields->addFieldToTab('Root.Projects', new HtmlEditorField('Project1Content', 'Text'));
    $uploadFieldProject1 = new UploadField($name = 'Project1Image', $title = 'Image');
    $uploadFieldProject1->setCanUpload(false);
    $fields->addFieldToTab('Root.Projects', $uploadFieldProject1);
    $fields->addFieldToTab('Root.Projects', new TextField('Project1ImageCredit', 'Credit'));
    $fields->addFieldToTab('Root.Projects', new TreeDropdownField('Project1ID', 'Project', 'SiteTree'));

    $fields->addFieldToTab('Root.Projects', new LiteralField ('literalfield', '<strong>Project 2</strong>'));
    $fields->addFieldToTab('Root.Projects', new HtmlEditorField('Project2Content', 'Text'));
    $uploadFieldProject2 = new UploadField($name = 'Project2Image', $title = 'Image');
    $uploadFieldProject2->setCanUpload(false);
    $fields->addFieldToTab('Root.Projects', $uploadFieldProject2);
    $fields->addFieldToTab('Root.Projects', new TextField('Project2ImageCredit', 'Credit'));
    $fields->addFieldToTab('Root.Projects', new TreeDropdownField('Project2ID', 'Project', 'SiteTree'));

    // keep up
    $uploadField3 = new UploadField($name = 'KeepUpImage', $title = 'Image');
    $uploadField3->setCanUpload(false);
    $fields->addFieldToTab('Root.KeepUp', $uploadField3);
    $fields->addFieldToTab('Root.KeepUp', new TextField('KeepUpImageCredit', 'Credit'));
    $fields->addFieldToTab('Root.KeepUp', new HtmlEditorField('KeepUpText', 'Text'));

    // impact
    $uploadField4 = new UploadField($name = 'ImpactImage', $title = 'Image');
    $uploadField4->setCanUpload(false);
    $fields->addFieldToTab('Root.Impact', $uploadField4);
    $fields->addFieldToTab('Root.Impact', new TextField('ImpactImageCredit', 'Credit'));

    // video
    $uploadFieldVideo = new UploadField('HeroVideoFile', 'Hero Video');
    $uploadFieldVideo->setAllowedExtensions(array('m4v', 'mp4'));
    $uploadFieldVideo->setCanUpload(false);

    $fields->addFieldToTab('Root.Video', $uploadFieldVideo);

    // social
    $fields->addFieldToTab('Root.Social', new TextField('SocialIGLink', 'Instagram URL'));
    $fields->addFieldToTab('Root.Social', new TextField('SocialFBLink', 'Facebook URL'));
    $fields->addFieldToTab('Root.Social', new TextField('SocialVimeoLink', 'Vimeo URL'));

    // supported
    $config = GridFieldConfig_RelationEditor::create();
    $config->removeComponentsByType('GridFieldPaginator');
    $config->removeComponentsByType('GridFieldPageCount');
    $config->addComponent(new GridFieldSortableRows('SortID'));

    $supporterElementField = new GridField(
      'SupporterElements', // Field name
      'Supporter Element', // Field title
      $this->SupporterElements(),
      $config
    );

    $fields->addFieldToTab('Root.Supported', $supporterElementField); 

    return $fields;
  }

}
class HomePage_Controller extends Page_Controller {
  private static $allowed_actions = array (
  );

  public function init() {
    parent::init();

    $this->HomePage = $this;

    $bShowSplash = true;

    if (Session::get('splashshown')) {
      $bShowSplash = false;
    }
    $this->ShowSplash = $bShowSplash;    

    Session::clear('splashshown');
  }
}
