# Notification System Implementation Summary

## Overview
Implemented a comprehensive notification system with role-based document handling for the CRAD system.

## Key Features Implemented

### 1. Student Submission Notifications
- **When**: Student submits or resubmits a document
- **Who Gets Notified**: Coordinator of the same department
- **Message**: "New submission from Group {group_no}: {document_name}"
- **Implementation**: `StudentSubmissionController@store` and `StudentSubmissionController@resubmit`

### 2. Coordinator Approval/Rejection Notifications
- **When**: Coordinator approves or rejects a document
- **Who Gets Notified**: Student who submitted the document
- **Restriction**: Coordinator CANNOT handle clearance documents
- **Message**: 
  - Approved: "Your submission '{document}' has been approved by coordinator."
  - Rejected: "Your submission '{document}' has been rejected. Please revise and resubmit."
- **Implementation**: `CoordinatorController@updateSubmissionStatus`

### 3. Admin Document Handling
- **Permissions**: Admin can handle ALL documents including clearance
- **When**: Admin approves/rejects any document
- **Who Gets Notified**: 
  - Student (always)
  - Coordinator (only for clearance documents)
- **Message**:
  - To Student: "Your submission '{document}' has been approved/rejected by admin."
  - To Coordinator: "Admin {status} clearance for Group {group_no}: {document}"
- **Implementation**: `AdminController@updateStatus`

### 4. Clearance Document Restrictions
- **Coordinator**: Cannot view or handle documents with "clearance" in the name
- **Admin**: Full access to all documents including clearance
- **Filter**: `CoordinatorController@getSubmissionsByGroup` filters out clearance documents

### 5. Auto-Refresh After Submission
- Success modal displays after upload
- Page automatically reloads when modal is closed
- Shows updated submission status immediately
- **Implementation**: `submission.blade.php` JavaScript

## Files Modified

### Controllers
1. **StudentSubmissionController.php**
   - Added coordinator notification on submission
   - Added coordinator notification on resubmission
   - Support for both file uploads and Google Drive links

2. **StudentController.php**
   - Added coordinator notification on store
   - Added `getNotifications()` API method

3. **CoordinatorController.php**
   - Added clearance document restriction
   - Added student notification on approve/reject
   - Added `getNotifications()` API method
   - Filter clearance documents from view

4. **AdminController.php**
   - Added student notification on status update
   - Added coordinator notification for clearance documents
   - Full access to all document types

### Routes (web.php)
- Added `/api/notifications` for students
- Added `/api/coordinator/notifications` for coordinators

### Views
- **submission.blade.php**: Added auto-reload after success modal

## Database Structure

### Notifications Table
```php
- user_id (who receives the notification)
- type (submission, resubmission, approved, rejected, clearance_update)
- message (notification text)
- submission_id (related submission)
- read (boolean)
- created_at
- updated_at
```

## Notification Flow

### Student Submits Document
```
Student → Submit → Create Submission → Notify Coordinator → Success Modal → Reload
```

### Coordinator Reviews (Non-Clearance)
```
Coordinator → Approve/Reject → Update Status → Notify Student
```

### Coordinator Tries Clearance
```
Coordinator → Attempt → 403 Error → "Only Admin can handle clearance documents"
```

### Admin Reviews Any Document
```
Admin → Approve/Reject → Update Status → Notify Student
If Clearance → Also Notify Coordinator
```

## API Endpoints

### Student Notifications
- **GET** `/api/notifications`
- **Response**: 
```json
{
  "notifications": [...],
  "unread_count": 5
}
```

### Coordinator Notifications
- **GET** `/api/coordinator/notifications`
- **Response**: 
```json
{
  "notifications": [...],
  "unread_count": 3
}
```

## Security Features
1. Role-based access control
2. Department-based filtering
3. Clearance document restrictions for coordinators
4. User ownership verification on resubmissions

## Testing Checklist
- [ ] Student submits document → Coordinator receives notification
- [ ] Student resubmits document → Coordinator receives notification
- [ ] Coordinator approves non-clearance → Student receives notification
- [ ] Coordinator rejects non-clearance → Student receives notification
- [ ] Coordinator tries clearance → Gets 403 error
- [ ] Admin approves clearance → Student AND Coordinator receive notifications
- [ ] Admin approves non-clearance → Only student receives notification
- [ ] Success modal shows after submission
- [ ] Page reloads after modal closes
- [ ] Submissions list updates with new status

## Notes
- Notifications are stored in database for persistence
- Unread count is calculated in real-time
- Clearance detection is case-insensitive
- Google Drive links are supported for manuscripts
- File uploads are supported for other documents
