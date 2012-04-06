<?php


    class Dashboard extends OwnedSecurableItem
    {
        const DEFAULT_USER_LAYOUT_ID = 1;

        public static function getByLayoutId($layoutId)
        {
            assert('is_integer($layoutId) && $layoutId >= 1');
            $bean = R::findOne('dashboard', "layoutid = $layoutId");
            assert('$bean === false || $bean instanceof RedBean_OODBBean');
            if ($bean === false)
            {
                throw new NotFoundException();
            }
            return self::makeModel($bean);
        }

        public static function getByLayoutIdAndUser($layoutId, $user)
        {
            assert('is_integer($layoutId) && $layoutId >= 1');
            assert('$user instanceof User && $user->id > 0');
            $sql = 'select dashboard.id id '            .
                   'from dashboard, ownedsecurableitem '                          .
                   'where ownedsecurableitem.owner__user_id = ' . $user->id       .
                   ' and dashboard.ownedsecurableitem_id = ownedsecurableitem.id '.
                   ' and layoutid = ' . $layoutId                                 .
                   ' order by layoutId;';
            $ids = R::getCol($sql);
            assert('count($ids) <= 1');
            if (count($ids) == 0)
            {
                if ($layoutId == Dashboard::DEFAULT_USER_LAYOUT_ID)
                {
                    return Dashboard::setDefaultDashboardForUser($user);
                }
                throw new NotFoundException();
            }
            $bean = R::load(RedBeanModel::getTableName('Dashboard'), $ids[0]);
            assert('$bean === false || $bean instanceof RedBean_OODBBean');
            if ($bean === false)
            {
                throw new NotFoundException();
            }
            return self::makeModel($bean);
        }

        public static function getRowsByUserId($userId)
        {
            assert('is_integer($userId) && $userId >= 1');
            $sql = 'select dashboard.id id, dashboard.name name, layoutid layoutId ' .
                   'from dashboard, ownedsecurableitem '                             .
                   'where ownedsecurableitem.owner__user_id = ' . $userId            .
                   ' and dashboard.ownedsecurableitem_id = ownedsecurableitem.id '   .
                   'order by layoutId;';
            return R::getAll($sql);
        }

        public static function getNextLayoutId()
        {
            return max(2, R::getCell('select max(layoutId) + 1 from dashboard'));
        }

        public function __toString()
        {
            try
            {
                if (trim($this->name) == '')
                {
                    return Yii::t('Default', '(Unnamed)');
                }
                return $this->name;
            }
            catch (AccessDeniedSecurityException $e)
            {
                return '';
            }
        }

        public static function getDefaultMetadata()
        {
            $metadata = parent::getDefaultMetadata();
            $metadata[__CLASS__] = array(
                'members' => array(
                    'layoutId',
                    'layoutType',
                    'isDefault',
                    'name',
                ),
                'rules' => array(
                    array('isDefault',  'boolean'),
                    array('layoutId',   'required'),
                    array('layoutId',   'type',   'type' => 'number'),
                    array('layoutType', 'required'),
                    array('layoutType', 'type',   'type' => 'string'),
                    array('layoutType', 'length', 'max' => 10),
                    array('name',       'required'),
                    array('name',       'type',   'type' => 'string'),
                    array('name',       'length', 'min' => 3, 'max' => 64),
                ),
                'defaultSortAttribute' => 'name'
            );
            return $metadata;
        }

        /**
         * Used to set the default dashboard information
         * for dashboard layoutId=1 for each user
         * @return Dashboard model.
         */
        private static function setDefaultDashboardForUser($user)
        {
            assert('$user instanceof User && $user->id > 0');
            $dashboard             = new Dashboard();
            $dashboard->name       = Yii::t('Default', 'Dashboard');
            $dashboard->layoutId   = Dashboard::DEFAULT_USER_LAYOUT_ID;
            $dashboard->owner      = $user;
            $dashboard->layoutType = '50,50'; // Not Coding Standard
            $dashboard->isDefault  = true;
            $saved                 = $dashboard->save();
            assert('$saved'); // TODO - deal with the properly.
            return $dashboard;
        }

        public static function isTypeDeletable()
        {
            return true;
        }

        public static function getModuleClassName()
        {
            return 'HomeModule';
        }

        /**
         * BEFORE ADDING TO THIS ARRAY - Remember to change the assertion in JuiPortlets:::init()
         */
        public static function getLayoutTypesData()
        {
            return array(
                '100'   => Yii::t('Default', '1 Column'),
                '50,50' => Yii::t('Default', '2 Columns'), // Not Coding Standard
                '75,25' => Yii::t('Default', '2 Columns Left Strong'), // Not Coding Standard
            );
        }
    }
?>
