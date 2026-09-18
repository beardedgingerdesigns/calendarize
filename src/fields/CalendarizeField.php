<?php
/**
 * Calendarize plugin for Craft CMS 3.x
 *
 * Calendar element types
 *
 * @link      https://union.co
 * @copyright Copyright (c) 2018 Franco Valdes
 */

namespace unionco\calendarize\fields;

use Craft;
use craft\i18n\Locale;
use craft\helpers\Json;
use craft\base\Element;
use craft\db\Query;
use yii\db\Expression;
use yii\db\ExpressionInterface;
use unionco\calendarize\records\CalendarizeRecord;
use craft\base\Field;
use craft\base\ElementInterface;
use unionco\calendarize\Calendarize;
use craft\base\PreviewableFieldInterface;
use craft\elements\db\ElementQueryInterface;
use unionco\calendarize\assetbundles\fieldbundle\FieldAssetBundle;

/**
 * @author    Franco Valdes
 * @package   Calendarize
 * @since     1.0.0
 */
class CalendarizeField extends Field implements PreviewableFieldInterface
{
    // Public Properties
    // =========================================================================

    /**
     * @var datetime
     */
    public $startDate;

    /**
     * @var datetime
     */
    public $endDate;

    /**
     * @var boolean
     */
    public $repeats = false;

    /**
     * @var boolean
     */
    public $allDay = false;
    
    /**
     * @var array
     */
    public $days = [];

    /**
     * @var string
     */
    public $endRepeat = NULL;

    /**
     * @var datetime
     */
    public $endRepeatDate = NULL;

    /**
     * @var array
     */
    public $exceptions = [];

    /**
     * @var array
     */
    public $timeChanges = [];

    /**
     * @var string
     */
    public $repeatType = NULL;

    /**
     * @var string
     */
    public $months = null;

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('calendarize', 'Calendarize');
    }

    /**
	 * @inheritdoc
	 */
	public static function hasContentColumn(): bool
	{
		return false;
    }
    
    /**
     * Recurrence data lives in calendarize, not elements_sites.content.
     */
    public static function dbType(): array|string|null
    {
        return null;
    }

    public static function phpType(): string
    {
        return '\\unionco\\calendarize\\models\\CalendarizeModel';
    }

    /**
     * Craft 5 calls this instead of modifyElementsQuery(). Preserve the legacy
     * truthy filter (has a recurrence record), scoped to the requested fields.
     */
    public static function queryCondition(
        array $instances,
        mixed $value,
        array &$params,
    ): array|string|ExpressionInterface|false|null {
        if (!$value) {
            return null;
        }

        return ['exists', (new Query())
            ->select(new Expression('1'))
            ->from(['calendarize' => CalendarizeRecord::tableName()])
            ->where('[[calendarize.ownerId]] = [[elements.id]]')
            ->andWhere('[[calendarize.ownerSiteId]] = [[elements_sites.siteId]]')
            ->andWhere(['calendarize.fieldId' => array_map(fn($field) => $field->id, $instances)])];
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        return $rules;
    }

    /**
	 * @inheritdoc
	 */
	public function getElementValidationRules(): array
	{
		return [
			[CalendarizeValidator::class, 'on' => Element::SCENARIO_LIVE],
		];
	}

    /**
     * @inheritdoc
     */
    public function normalizeValue(mixed $value, ?\craft\base\ElementInterface $element = null): mixed
    {
        return Calendarize::$plugin->calendar->getField($this, $element, $value);
    }

    /**
	 * @inheritdoc
	 */
	public function modifyElementsQuery(ElementQueryInterface $query, mixed $value): void
	{
		// For whatever reason, this function can be
		// run BEFORE Calendarize has been initialized
		if (!Calendarize::$plugin) {
            return;
        }

		Calendarize::$plugin->calendar->modifyElementsQuery($query, $value);

		return;
    }

    /**
	 * @inheritdoc
	 */
	public function afterElementSave(ElementInterface $element, bool $isNew): void
	{
		Calendarize::$plugin->calendar->saveField($this, $element);
		parent::afterElementSave($element, $isNew);
    }

    /**
     * @inheritdoc
     */
    public function getTableAttributeHtml(mixed $value, ElementInterface $element): string
    {
        if (empty($value->startDate) && empty($value->endDate)) {
            return '-';
        }
        
        $hr = $value->readable(['locale' => Craft::$app->locale->id]);
        $html = "<span title=\"{$hr}\">";
        
        if ($value->hasPassed()) {
            $html .= "<b>" . Craft::t('calendarize', 'Last Occurrence') . ":</b>";
        } else {
            $html .= "<b>" . Craft::t('calendarize', 'Next Occurrence') . ":</b>";
        }

        $html .= "<br/>" . $value->next()->format('l, m/d/Y @ h:i:s a');

        return $html;
    }

    /**
     * @inheritdoc
     */
    protected function inputHtml(mixed $value, ?ElementInterface $element, bool $inline): string
    {
        // Register our asset bundle
        $view = Craft::$app->getView();

        // Get our id and namespace
        $id = $view->formatInputId($this->handle);
        $namespacedId = $view->namespaceInputId($id);
        $locale = Craft::$app->getLocale()->id;
        $dateFormat = Craft::$app->getLocale()->getDateFormat(Locale::LENGTH_MEDIUM);

        $view->registerAssetBundle(FieldAssetBundle::class);
        $args = implode(', ', array_map([Json::class, 'encode'], [$namespacedId, $locale, $dateFormat]));
        $view->registerJs("new Calendarize($args);");

        // Render the input template
        return $view->renderTemplate(
            'calendarize/_components/fields/CalendarizeField_input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
                'id' => $id,
                'namespacedId' => $namespacedId,
            ]
        );
    }
}
