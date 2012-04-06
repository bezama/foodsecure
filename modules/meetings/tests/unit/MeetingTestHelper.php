<?php


    class MeetingTestHelper
    {
        public static function createMeetingByNameForOwner($name, $owner)
        {
            $startStamp       = DateTimeUtil::convertTimestampToDbFormatDateTime(time() + 10000);
            $endStamp         = DateTimeUtil::convertTimestampToDbFormatDateTime(time() + 11000);
            $meeting = new Meeting();
            $meeting->name             = $name;
            $meeting->owner            = $owner;
            $meeting->location         = 'my location';
            $meeting->category->value  = 'Call';
            $meeting->startDateTime    = $startStamp;
            $meeting->endDateTime      = $endStamp;
            $meeting->description      = 'my test description';
            $saved = $meeting->save();
            assert('$saved');
            return $meeting;
        }

        public static function createMeetingWithOwnerAndRelatedAccount($name, $owner, $account)
        {
            $startStamp       = DateTimeUtil::convertTimestampToDbFormatDateTime(time() + 10000);
            $endStamp         = DateTimeUtil::convertTimestampToDbFormatDateTime(time() + 11000);
            $meeting = new Meeting();
            $meeting->name             = $name;
            $meeting->owner            = $owner;
            $meeting->location         = 'my location';
            $meeting->category->value  = 'Call';
            $meeting->startDateTime    = $startStamp;
            $meeting->endDateTime      = $endStamp;
            $meeting->description      = 'my test description';
            $meeting->activityItems->add($account);
            $saved = $meeting->save();
            assert('$saved');
            return $meeting;
        }

        public static function createCategories()
        {
            $values = array(
                'Meeting',
                'Call',
                'Event',
            );
            $typeFieldData                 = CustomFieldData::getByName('MeetingCategories');
            $typeFieldData->serializedData = serialize($values);
            $saved                         = $typeFieldData->save();
            if (!$saved)
            {
                throw new NotSupportedException();
            }
        }
    }
?>
