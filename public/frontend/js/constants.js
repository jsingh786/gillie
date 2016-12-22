/**
 * Created by hkaur5 on 9/20/2016.
 */

/**
 * Json containing messages where keys are message strings delimited with underscores
 * @type {{profile_updated: string, your_album_is_untitled: string, album_name_should_be_no_more_than_30_characters: string, album_is_saved: string, album_is_deleted: string, videos_added: string, video_deleted: string, photos_added_to_the_album: string, photo_deleted: string}}
 */
var constants = {
    //Generic messages
    'oops_some_error_occurred_please_try_again':'Oops! Some error occurred. Please try again.',
    'please_fix_the_errors': 'Please fix the error(s)',
    'removing': 'Removing...',
    'removed': 'Removed.',
    'added': 'Added.',
    'adding': 'Adding...',
    'updated': 'Updated',
    'updating': 'Updating...',
    'you_have_not_made_any_changes': 'You have not made any changes.',
    'no_records_found' : 'Oops! No records found as per your search criteria. Please try again with different filters.',

    //profile section
    'profile_updated':'Profile updated.',
    'your_form_has_some_errors_profile':'Your form has some errors. Please fix them to update your profile.',//profile section

    //Album section
    'your_album_is_untitled':'Your album is untitled.',
    'album_name_should_be_no_more_than_30_characters':'Album name should not be more than 30 characters.',
    'album_saved':'Album saved.',
    'album_name_cannot_be_empty':'Album name cannot be empty.',
    'album_deleted':'Album deleted.',
    'you_have_added_no_photos_to_post':'You have added no photos to post.',
    'album_name_updated':'Album name updated',
    'videos_added':'Video(s) added.',//video section
    'video_deleted':'Video deleted.',
    'you_have_added_no_videos_to_post':'You have added no videos to post.',
    'video_thumbnails_not_deleted':'Some error occurred while deleting video thumbnail.',
    'photos_added_to_the_album':'Photos are added to the album.',//photos section
    'photo_deleted':'Photo deleted.',

    //Forums Section
    'forum_saved': 'Forum Saved.',
    'add_comment': 'Add Comment',

    //Notification Section
    'notification_deleted':'Notification Deleted.',
    'notification_already_deleted': 'This Notification is already deleted.',

    //News feed
    'please_add_text_to_post_an_update': 'Please add text for posting an update.',
    'you_can_add_one_video_at_a_time_are_you_sure_you_want_to_remove_previously_added_video': 'You can add one video at a time. Are you sure you want to remove previously added video?',
    'are_you_sure_you_want_to_discard_video_update_click_yes_if_you_want_to_continue': 'Are you sure you want to discard your video update, click yes if you want to continue.',
    'are_you_sure_you_want_to_discard_photos_update_click_yes_if_you_want_to_continue': 'Are you sure you want to discard your photos update, click yes if you want to continue.'
}