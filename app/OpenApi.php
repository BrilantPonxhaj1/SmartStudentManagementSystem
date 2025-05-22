<?php

namespace App;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="My API Documentation",
 *     version="1.0.0",
 *     description="API documentation for my application."
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *
 * // ========= Schemas ============
 *
 * @OA\Schema(
 *     schema="Student",
 *     type="object",
 *     @OA\Property(property="id",         type="integer", format="int64"),
 *     @OA\Property(property="first_name", type="string"),
 *     @OA\Property(property="last_name",  type="string"),
 *     @OA\Property(property="email",      type="string", format="email"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="StoreStudentRequest",
 *     type="object",
 *     required={"first_name","last_name","email"},
 *     @OA\Property(property="first_name", type="string"),
 *     @OA\Property(property="last_name",  type="string"),
 *     @OA\Property(property="email",      type="string", format="email")
 * )
 * @OA\Schema(
 *      schema="UpdateStudentRequest",
 *      type="object",
 *      @OA\Property(property="first_name", type="string", example="Alice"),
 *      @OA\Property(property="last_name",  type="string", example="Smith"),
 *      @OA\Property(property="email",      type="string", format="email", example="alice.updated@example.com")
 *  )
 *
 * @OA\Schema(
 *     schema="Department",
 *     type="object",
 *     @OA\Property(property="id",            type="integer"),
 *     @OA\Property(property="name",          type="string"),
 *     @OA\Property(property="code",          type="string"),
 *     @OA\Property(property="university_id", type="integer"),
 *     @OA\Property(property="created_at",    type="string", format="date-time"),
 *     @OA\Property(property="updated_at",    type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="StoreDepartmentRequest",
 *     type="object",
 *     required={"name","code","university_id"},
 *     @OA\Property(property="name",          type="string"),
 *     @OA\Property(property="code",          type="string"),
 *     @OA\Property(property="university_id", type="integer")
 * )
 *
 * @OA\Schema(
 *     schema="Exam",
 *     type="object",
 *     @OA\Property(property="id",           type="integer", example=1),
 *     @OA\Property(property="title",        type="string",  example="Midterm Exam"),
 *     @OA\Property(property="date",         type="string",  format="date", example="2025-06-01"),
 *     @OA\Property(property="semester_id",  type="integer", example=3),
 *     @OA\Property(property="created_at",   type="string",  format="date-time"),
 *     @OA\Property(property="updated_at",   type="string",  format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="StoreExamRequest",
 *     type="object",
 *     required={"title","date","semester_id"},
 *     @OA\Property(property="title",       type="string", example="Midterm Exam"),
 *     @OA\Property(property="date",        type="string", format="date", example="2025-06-01"),
 *     @OA\Property(property="semester_id", type="integer", example=3)
 * )
 *
 * @OA\Schema(
 *     schema="UpdateExamRequest",
 *     type="object",
 *     @OA\Property(property="title",       type="string", example="Final Exam"),
 *     @OA\Property(property="date",        type="string", format="date", example="2025-07-01"),
 *     @OA\Property(property="semester_id", type="integer", example=3)
 * )
 *
 * @OA\Schema(
 *     schema="Professor",
 *     type="object",
 *     @OA\Property(property="id",            type="integer", example=1),
 *     @OA\Property(property="first_name",    type="string",  example="Jane"),
 *     @OA\Property(property="last_name",     type="string",  example="Doe"),
 *     @OA\Property(property="email",         type="string",  format="email", example="jane.doe@example.com"),
 *     @OA\Property(property="department_id", type="integer", example=4),
 *     @OA\Property(property="created_at",    type="string",  format="date-time"),
 *     @OA\Property(property="updated_at",    type="string",  format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="StoreProfessorRequest",
 *     type="object",
 *     required={"first_name","last_name","email","department_id"},
 *     @OA\Property(property="first_name",    type="string", example="Jane"),
 *     @OA\Property(property="last_name",     type="string", example="Doe"),
 *     @OA\Property(property="email",         type="string", format="email", example="jane.doe@example.com"),
 *     @OA\Property(property="department_id", type="integer", example=4)
 * )
 *
 * @OA\Schema(
 *     schema="UpdateProfessorRequest",
 *     type="object",
 *     @OA\Property(property="first_name",    type="string", example="Jane"),
 *     @OA\Property(property="last_name",     type="string", example="Doe"),
 *     @OA\Property(property="email",         type="string", format="email", example="jane.doe@example.com"),
 *     @OA\Property(property="department_id", type="integer", example=4)
 * )
 * @OA\Schema(
 *    schema="Semester",
 *    type="object",
 *    @OA\Property(property="id",            type="integer", example=1),
 *    @OA\Property(property="name",          type="string",  example="Fall 2025"),
 *    @OA\Property(property="start_date",    type="string",  format="date",     example="2025-09-01"),
 *    @OA\Property(property="end_date",      type="string",  format="date",     example="2025-12-15"),
 *    @OA\Property(property="university_id", type="integer", example=1),
 *    @OA\Property(property="created_at",    type="string",  format="date-time"),
 *    @OA\Property(property="updated_at",    type="string",  format="date-time")
 *  )
 *
 * @OA\Schema(
 *    schema="StoreSemesterRequest",
 *    type="object",
 *    required={"name","start_date","end_date","university_id"},
 *    @OA\Property(property="name",          type="string", example="Fall 2025"),
 *    @OA\Property(property="start_date",    type="string", format="date", example="2025-09-01"),
 *    @OA\Property(property="end_date",      type="string", format="date", example="2025-12-15"),
 *    @OA\Property(property="university_id", type="integer", example=1)
 *  )
 *
 * @OA\Schema(
 *    schema="UpdateSemesterRequest",
 *    type="object",
 *    @OA\Property(property="name",          type="string", example="Spring 2026"),
 *    @OA\Property(property="start_date",    type="string", format="date", example="2026-01-10"),
 *    @OA\Property(property="end_date",      type="string", format="date", example="2026-05-20"),
 *    @OA\Property(property="university_id", type="integer", example=1)
 *  )
 * @OA\Schema(
 *      schema="Subject",
 *      type="object",
 *      @OA\Property(property="id",          type="integer", example=1),
 *      @OA\Property(property="name",        type="string",  example="Calculus I"),
 *      @OA\Property(property="code",        type="string",  example="MATH101"),
 *      @OA\Property(property="department_id", type="integer", example=2),
 *      @OA\Property(property="credits",     type="integer", example=3),
 *      @OA\Property(property="created_at",  type="string",  format="date-time"),
 *      @OA\Property(property="updated_at",  type="string",  format="date-time")
 *  )
 *
 * @OA\Schema(
 *      schema="StoreSubjectRequest",
 *      type="object",
 *      required={"name","code","department_id","credits"},
 *      @OA\Property(property="name",        type="string",  example="Calculus I"),
 *      @OA\Property(property="code",        type="string",  example="MATH101"),
 *      @OA\Property(property="department_id", type="integer", example=2),
 *      @OA\Property(property="credits",     type="integer", example=3)
 *  )
 *
 * @OA\Schema(
 *      schema="UpdateSubjectRequest",
 *      type="object",
 *      @OA\Property(property="name",        type="string",  example="Calculus I"),
 *      @OA\Property(property="code",        type="string",  example="MATH101"),
 *      @OA\Property(property="department_id", type="integer", example=2),
 *      @OA\Property(property="credits",     type="integer", example=3)
 *  )
 * @OA\Schema(
 *      schema="University",
 *      type="object",
 *      @OA\Property(property="id",           type="integer", example=1),
 *      @OA\Property(property="name",         type="string",  example="Example University"),
 *      @OA\Property(property="city",         type="string",  example="Belgrade"),
 *      @OA\Property(property="country",      type="string",  example="Serbia"),
 *      @OA\Property(property="created_at",   type="string", format="date-time", example="2025-05-20T14:25:43Z"),
 *      @OA\Property(property="updated_at",   type="string", format="date-time", example="2025-05-21T09:17:12Z")
 *  )
 *
 * @OA\Schema(
 *      schema="StoreUniversityRequest",
 *      type="object",
 *      required={"name","city","country"},
 *      @OA\Property(property="name",         type="string", example="Example University"),
 *      @OA\Property(property="city",         type="string", example="Belgrade"),
 *      @OA\Property(property="country",      type="string", example="Serbia")
 *  )
 *
 * @OA\Schema(
 *      schema="UpdateUniversityRequest",
 *      type="object",
 *      @OA\Property(property="name",         type="string", example="Updated University"),
 *      @OA\Property(property="city",         type="string", example="Belgrade"),
 *      @OA\Property(property="country",      type="string", example="Serbia")
 *  )
 * @OA\Schema(
 *      schema="Assignment",
 *      type="object",
 *      @OA\Property(property="id",          type="integer", format="int64", example=10),
 *      @OA\Property(property="title",       type="string",               example="Homework 1"),
 *      @OA\Property(property="description", type="string",               example="Read chapters 1–3 and solve problems"),
 *      @OA\Property(property="due_date",    type="string", format="date", example="2025-06-15"),
 *      @OA\Property(property="created_at",  type="string", format="date-time", example="2025-05-20T14:25:43Z"),
 *      @OA\Property(property="updated_at",  type="string", format="date-time", example="2025-05-21T09:17:12Z")
 *  )
 *
 * @OA\Schema(
 *      schema="StoreAssignmentsRequest",
 *      type="object",
 *      required={"title","description","due_date"},
 *      @OA\Property(property="title",       type="string", example="Homework 1"),
 *      @OA\Property(property="description", type="string", example="Read chapters 1–3 and solve problems"),
 *      @OA\Property(property="due_date",    type="string", format="date", example="2025-06-15")
 *  )
 *
 * @OA\Schema(
 *      schema="UpdateAssignmentsRequest",
 *      type="object",
 *      @OA\Property(property="title",       type="string", example="Homework 1"),
 *      @OA\Property(property="description", type="string", example="Read chapters 1–4 and solve problems"),
 *      @OA\Property(property="due_date",    type="string", format="date", example="2025-06-20")
 *  )
 * @OA\Schema(
 *      schema="CourseOffering",
 *      type="object",
 *      @OA\Property(property="id",            type="integer", example=10),
 *      @OA\Property(property="course_id",     type="integer", example=5),
 *      @OA\Property(property="professor_id",  type="integer", example=3),
 *      @OA\Property(property="semester_id",   type="integer", example=2),
 *      @OA\Property(property="capacity",      type="integer", example=30),
 *      @OA\Property(property="created_at",    type="string",  format="date-time", example="2025-05-20T14:25:43Z"),
 *      @OA\Property(property="updated_at",    type="string",  format="date-time", example="2025-05-21T09:17:12Z")
 *  )
 *
 * @OA\Schema(
 *      schema="StoreCourseOfferingRequest",
 *      type="object",
 *      required={"course_id","professor_id","semester_id","capacity"},
 *      @OA\Property(property="course_id",    type="integer", example=5),
 *      @OA\Property(property="professor_id", type="integer", example=3),
 *      @OA\Property(property="semester_id",  type="integer", example=2),
 *      @OA\Property(property="capacity",     type="integer", example=30)
 *  )
 *
 * @OA\Schema(
 *      schema="UpdateCourseOfferingRequest",
 *      type="object",
 *      @OA\Property(property="course_id",    type="integer", example=5),
 *      @OA\Property(property="professor_id", type="integer", example=3),
 *      @OA\Property(property="semester_id",  type="integer", example=2),
 *      @OA\Property(property="capacity",     type="integer", example=25)
 *  )
 * @OA\Schema(
 *    schema="Enrollment",
 *    type="object",
 *    @OA\Property(property="id",                  type="integer", example=123),
 *    @OA\Property(property="student_id",          type="integer", example=45),
 *    @OA\Property(property="course_offering_id",  type="integer", example=7),
 *    @OA\Property(property="created_at",          type="string",  format="date-time", example="2025-05-20T14:25:43Z")
 *  )
 */
class OpenApi
{
    // Used only for swagger-php annotations
}
