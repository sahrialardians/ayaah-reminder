# Product Requirements Document (PRD)

## Project: Ayaah Reminder App

A simple yet powerful app to help Muslims track and continue their Qur’an reading by saving the last ayah read and sending gentle reminders.

---

## 1. Purpose

The purpose of the Ayaah Reminder App is to assist Muslims in maintaining consistency in their Qur’an reading by:
- Saving the last ayah read
- Sending reminder notifications
- Offering a clean and minimalistic experience

---

## 2. Target Audience

- Muslims who regularly read the Qur’an
- Beginners who want to build consistent habits
- Busy individuals looking for a lightweight way to track their reading progress

---

## 3. Features

### Core Features

1. **User Authentication**
   - Sign up, Login, Logout (via email/password)
   - JWT-based authentication for API

2. **Save Last Ayah**
   - Select and save the last ayah read
   - Save surah, ayah number, and timestamp

3. **View Last Ayah**
   - Endpoint to retrieve saved last ayah
   - Helpful for resuming reading

4. **Reminder Notifications**
   - Users receive daily or custom reminders
   - Email or push notifications

---

## 4. Tech Stack

- **Backend:** Laravel 13 (RESTful API)
- **Database:** MySQL
- **Auth:** Laravel Sanctum
- **Notifications:** Laravel Scheduler + Resend, FCM (optional for mobile push)

---

## 5. API Endpoints

| Method | Endpoint               | Description                     |
|--------|------------------------|---------------------------------|
| POST   | /api/register          | Register new user               |
| POST   | /api/login             | Login user                      |
| POST   | /api/logout            | Logout user                     |
| GET    | /api/ayah              | Get last saved ayah             |
| POST   | /api/ayah              | Save last ayah (surah, ayah)    |
| POST   | /api/reminder          | Set notification                |

---

## 6. UI/UX

For MVP:
- Landing page with high-convertion
- Focus on mobile-first design
- Dashboard to save/view ayah
- Option to manage reminder settings

---

## 7. Credits

Built by [@sahrialardians](https://twitter.com/sahrialardians)  
#buildinpublic #MuslimTech
