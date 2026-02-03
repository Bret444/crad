# Group Management System - Documentation

## Overview
A complete group management system for coordinators to manage student groups with credentials for student portal access.

## Features Implemented

### 1. Database Structure
- **Table**: `groups`
- **Fields**:
  - `id` - Primary key
  - `group_id` - Unique identifier (e.g., IT001, CRIM002)
  - `password` - Hashed password for student login
  - `department` - Department (BSIT, CRIM, EDUC, BSBA, Psychology, BSHM, BSTM)
  - `member1_name` to `member5_name` - Student names
  - `member1_student_id` to `member5_student_id` - Student IDs
  - `created_at`, `updated_at`, `deleted_at` - Timestamps

### 2. Functionality

#### Add Group
- Create new groups with unique Group ID
- Set password (minimum 6 characters)
- Select department
- Add up to 5 members with names and student IDs
- Password is automatically hashed for security

#### Edit Group
- Update group information
- Change Group ID (must remain unique)
- Update password (optional - leave blank to keep current)
- Modify member information
- Update department

#### Delete/Archive Group
- Soft delete groups (can be restored)
- Archived groups are hidden from main view
- Access archived groups via "Archived Groups" button

#### Restore Group
- View all archived groups
- Restore any archived group back to active status

#### Filter Groups
- Filter by department
- Quick search functionality

### 3. Security Features
- Passwords are hashed using Laravel's Hash facade
- Group IDs must be unique
- CSRF protection on all forms
- Soft deletes for data recovery

### 4. User Interface
- Clean, modern Bootstrap 5 design
- Responsive layout
- Modal-based forms
- Confirmation dialogs for destructive actions
- Success notifications
- Loading states

## Usage

### For Coordinators

1. **Access the page**: Navigate to "Manage Groups" from coordinator dashboard

2. **Add a new group**:
   - Click "Add Group" button
   - Enter Group ID (e.g., IT001, CRIM002)
   - Set a password (min 6 characters)
   - Select department
   - Fill in member details (up to 5 members)
   - Click "Create Group"

3. **Edit existing group**:
   - Click pencil icon on any group
   - Modify information as needed
   - Leave password blank to keep current password
   - Click "Update Group"

4. **Archive a group**:
   - Click trash icon on any group
   - Confirm the action
   - Group will be moved to archives

5. **Restore archived group**:
   - Click "Archived Groups" button
   - Find the group to restore
   - Click "Restore" button
   - Confirm the action

6. **Filter groups**:
   - Use department dropdown to filter by department

### For Students (Future Implementation)

Students will use the Group ID and Password to login to the student portal:
- Group ID: IT001
- Password: (set by coordinator)

## Files Created/Modified

1. **Migration**: `database/migrations/2026_01_26_055005_create_groups_table.php`
2. **Model**: `app/Models/Group.php`
3. **Controller**: `app/Http/Controllers/GroupController.php`
4. **View**: `resources/views/coordinator-manage-groups.blade.php`
5. **Routes**: `routes/web.php` (added group routes)

## Routes

- `GET /coordinator-manage-groups` - Display groups page
- `POST /groups` - Create new group
- `PUT /groups/{id}` - Update group
- `DELETE /groups/{id}` - Archive group
- `GET /groups/archived` - Get archived groups (AJAX)
- `POST /groups/{id}/restore` - Restore archived group

## Database Schema

```sql
CREATE TABLE groups (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    group_id VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    department VARCHAR(255) NOT NULL,
    member1_name VARCHAR(255) NULL,
    member1_student_id VARCHAR(255) NULL,
    member2_name VARCHAR(255) NULL,
    member2_student_id VARCHAR(255) NULL,
    member3_name VARCHAR(255) NULL,
    member3_student_id VARCHAR(255) NULL,
    member4_name VARCHAR(255) NULL,
    member4_student_id VARCHAR(255) NULL,
    member5_name VARCHAR(255) NULL,
    member5_student_id VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);
```

## Next Steps (Optional Enhancements)

1. **Student Login Integration**:
   - Modify student login to accept Group ID and Password
   - Create student authentication using group credentials

2. **Export Functionality**:
   - Export groups to Excel/CSV
   - Print group lists

3. **Bulk Operations**:
   - Import groups from CSV
   - Bulk delete/restore

4. **Advanced Features**:
   - Group project tracking
   - Member role assignment
   - Group communication system

## Support

For issues or questions, contact the development team.
