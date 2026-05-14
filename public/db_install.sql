--
-- PostgreSQL database dump
--

-- Dumped from database version 10.23 (Ubuntu 10.23-0ubuntu0.18.04.2)
-- Dumped by pg_dump version 16.0

-- Started on 2024-12-11 14:27:33

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 7 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: postgres
--

-- *not* creating schema, since initdb creates it


ALTER SCHEMA public OWNER TO postgres;

--
-- TOC entry 291 (class 1255 OID 117928)
-- Name: AddAgreementUser(bigint, bigint, smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddAgreementUser"("FltAgreementId" bigint, "FltUserId" bigint, "FltOrder" smallint) RETURNS TABLE(id bigint, "agreementId" bigint, "userId" bigint, note text, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, approved_at timestamp without time zone, viewed_at timestamp without time zone, "order" smallint)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	INSERT INTO "agreements_and_users" (
						"agreementId", 
						"userId",
						"created_at",
						"order"
						)
  			VALUES ("FltAgreementId",
					"FltUserId",
					current_timestamp,
				   	"FltOrder") 
			RETURNING "agreements_and_users"."id" INTO "Id";
 	RETURN QUERY SELECT "agreements_and_users"."id",
						"agreements_and_users"."agreementId",
						"agreements_and_users"."userId",
						"agreements_and_users"."note",
						"agreements_and_users"."created_at",
						"agreements_and_users"."updated_at",
						"agreements_and_users"."removed",
						"agreements_and_users"."refused_at",
						"agreements_and_users"."approved_at",
						"agreements_and_users"."viewed_at",
						"agreements_and_users"."order"
				   FROM "agreements_and_users"
				  WHERE "agreements_and_users"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddAgreementUser"("FltAgreementId" bigint, "FltUserId" bigint, "FltOrder" smallint) OWNER TO postgres;

--
-- TOC entry 292 (class 1255 OID 117929)
-- Name: AddAgreementUserNote(bigint, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddAgreementUserNote"("FltId" bigint, "FltNote" character varying, OUT "Result" bigint) RETURNS bigint
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "agreements_and_users" 
		SET "note" = "FltNote",  
			"updated_at" = current_timestamp
		WHERE "id" = "FltId";
	"Result" = 1;	
	RETURN;
END
$$;


ALTER FUNCTION public."AddAgreementUserNote"("FltId" bigint, "FltNote" character varying, OUT "Result" bigint) OWNER TO postgres;

--
-- TOC entry 293 (class 1255 OID 117930)
-- Name: AddAgreementUserWithoutCreatedAt(bigint, bigint, smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddAgreementUserWithoutCreatedAt"("FltAgreementId" bigint, "FltUserId" bigint, "FltOrder" smallint) RETURNS TABLE(id bigint, "agreementId" bigint, "userId" bigint, note text, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, approved_at timestamp without time zone, viewed_at timestamp without time zone, "order" smallint)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	INSERT INTO "agreements_and_users" (
						"agreementId", 
						"userId",
						"order"
						)
  			VALUES ("FltAgreementId",
					"FltUserId",
				   	"FltOrder") 
			RETURNING "agreements_and_users"."id" INTO "Id";
 	RETURN QUERY SELECT "agreements_and_users"."id",
						"agreements_and_users"."agreementId",
						"agreements_and_users"."userId",
						"agreements_and_users"."note",
						"agreements_and_users"."created_at",
						"agreements_and_users"."updated_at",
						"agreements_and_users"."removed",
						"agreements_and_users"."refused_at",
						"agreements_and_users"."approved_at",
						"agreements_and_users"."viewed_at",
						"agreements_and_users"."order"
				   FROM "agreements_and_users"
				  WHERE "agreements_and_users"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddAgreementUserWithoutCreatedAt"("FltAgreementId" bigint, "FltUserId" bigint, "FltOrder" smallint) OWNER TO postgres;

--
-- TOC entry 294 (class 1255 OID 117931)
-- Name: AddAssignmentAndAssignmentStatus(bigint, smallint, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddAssignmentAndAssignmentStatus"("FltAssignmentId" bigint, "FltAssignmentStatusId" smallint, "FltNote" character varying) RETURNS TABLE(id bigint, "assignmentId" bigint, "assignmentstatusId" smallint, created_at timestamp without time zone, updated_at timestamp without time zone, completed_at timestamp without time zone, note character varying)
    LANGUAGE plpgsql
    AS $$
DECLARE 
 	"Id" bigint;
BEGIN
 	INSERT INTO "assignments_and_assignmentstatuses" ("assignmentId", 
						 								"assignmentstatusId", 
														"created_at",
														"note")
  			VALUES ("FltAssignmentId", 
					"FltAssignmentStatusId", 
					current_timestamp,
				   "FltNote") 
			RETURNING "assignments_and_assignmentstatuses"."id" INTO "Id";
	RETURN QUERY SELECT "assignments_and_assignmentstatuses"."id",
						"assignments_and_assignmentstatuses"."assignmentId",
						"assignments_and_assignmentstatuses"."assignmentstatusId",
						"assignments_and_assignmentstatuses"."created_at",
						"assignments_and_assignmentstatuses"."updated_at",
						"assignments_and_assignmentstatuses"."removed",
						"assignments_and_assignmentstatuses"."note"
				FROM "assignments_and_assignmentstatuses" WHERE "assignments_and_assignmentstatuses"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddAssignmentAndAssignmentStatus"("FltAssignmentId" bigint, "FltAssignmentStatusId" smallint, "FltNote" character varying) OWNER TO postgres;

--
-- TOC entry 295 (class 1255 OID 117932)
-- Name: AddControl(bigint, bigint, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddControl"("FltUserId" bigint, "FltAssignmentId" bigint, "FltInitiatorId" bigint) RETURNS TABLE(id bigint, "userId" bigint, "assignmentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, viewed_at timestamp without time zone, "initiatorId" bigint)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	INSERT INTO "controls" (
						"userId",
						"assignmentId",
						"created_at",
						"initiatorId"
						)
  			VALUES ("FltUserId",
				"FltAssignmentId",
					current_timestamp,
					"FltInitiatorId"
				   ) 
			RETURNING "controls"."id" INTO "Id";
 	RETURN QUERY SELECT "controls"."id",
						"controls"."userId",
						"controls"."assignmentId",
						"controls"."created_at",
						"controls"."updated_at",
						"controls"."removed",
						"controls"."viewed_at",
						"controls"."initiatorId"
				   FROM "controls"
				  WHERE "controls"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddControl"("FltUserId" bigint, "FltAssignmentId" bigint, "FltInitiatorId" bigint) OWNER TO postgres;

--
-- TOC entry 296 (class 1255 OID 117933)
-- Name: AddDiruserAndDocument(bigint, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddDiruserAndDocument"("FltDiruserId" bigint, "FltDocumentId" bigint) RETURNS TABLE(id bigint, "diruserId" bigint, "documentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	INSERT INTO "dirusers_and_documents" ("diruserId", 
						 "documentId", 
						 "created_at")
  			VALUES ("FltDiruserId", 
					"FltDocumentId", 
					current_timestamp
					) RETURNING "dirusers_and_documents"."id" INTO "Id";
			RETURN QUERY SELECT "dirusers_and_documents"."id",
								"dirusers_and_documents"."diruserId",
								"dirusers_and_documents"."documentId",
								"dirusers_and_documents"."created_at",
								"dirusers_and_documents"."updated_at",
								"dirusers_and_documents"."removed"
						FROM "dirusers_and_documents" WHERE "dirusers_and_documents"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddDiruserAndDocument"("FltDiruserId" bigint, "FltDocumentId" bigint) OWNER TO postgres;

--
-- TOC entry 297 (class 1255 OID 117934)
-- Name: AddDocumentAndFile(bigint, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddDocumentAndFile"("FltDocumentId" bigint, "FltFileId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "fileId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	INSERT INTO "documents_and_files" (
						"documentId", 
						"fileId",
						"created_at"
						)
  			VALUES ("FltDocumentId",
					"FltFileId",
					current_timestamp) 
			RETURNING "documents_and_files"."id" INTO "Id";
 	RETURN QUERY SELECT "documents_and_files"."id",
						"documents_and_files"."documentId",
						"documents_and_files"."fileId",
						"documents_and_files"."created_at",
						"documents_and_files"."updated_at",
						"documents_and_files"."removed"
				   FROM "documents_and_files"
				  WHERE "documents_and_files"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddDocumentAndFile"("FltDocumentId" bigint, "FltFileId" bigint) OWNER TO postgres;

--
-- TOC entry 298 (class 1255 OID 117935)
-- Name: AddFileAndAddition(bigint, bigint, bigint, bigint, bigint, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddFileAndAddition"("FltFileId" bigint, "FltDocumentId" bigint, "FltAssignmentId" bigint, "FltFeedbackId" bigint, "FltBlogId" bigint, "FltAgreementAndUserId" bigint) RETURNS TABLE(id bigint, "fileId" bigint, "documentId" bigint, "assignmentId" bigint, "feedbackId" bigint, "blogId" bigint, "agreementAndUserId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	INSERT INTO "files_and_additions" (
						"fileId", 
						"documentId", 
						"assignmentId",
						"feedbackId",
						"blogId",
						"agreementAndUserId",
						"created_at"
						)
  			VALUES ("FltFileId",
					"FltDocumentId",
					"FltAssignmentId",
					"FltFeedbackId",
					"FltBlogId",
					"FltAgreementAndUserId",
					current_timestamp) 
			RETURNING "files_and_additions"."id" INTO "Id";
 	RETURN QUERY SELECT "files_and_additions"."id",
						"files_and_additions"."fileId",
						"files_and_additions"."documentId",
						"files_and_additions"."assignmentId",
						"files_and_additions"."feedbackId",
						"files_and_additions"."blogId",
						"files_and_additions"."agreementAndUserId",
						"files_and_additions"."created_at",
						"files_and_additions"."updated_at",
						"files_and_additions"."removed"
				   FROM "files_and_additions"
				  WHERE "files_and_additions"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddFileAndAddition"("FltFileId" bigint, "FltDocumentId" bigint, "FltAssignmentId" bigint, "FltFeedbackId" bigint, "FltBlogId" bigint, "FltAgreementAndUserId" bigint) OWNER TO postgres;

--
-- TOC entry 299 (class 1255 OID 117936)
-- Name: AddNewAcquaintance(bigint, bigint, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddNewAcquaintance"("FltDocumentId" bigint, "FltUserId" bigint, "FltInitiatorId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "userId" bigint, "initiatorId" bigint, seen_at timestamp without time zone, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	INSERT INTO "acquaintances" (
						"documentId", 
						"userId",
						"initiatorId",
						"created_at"
						)
  			VALUES ("FltDocumentId",
					"FltUserId",
					"FltInitiatorId",
					current_timestamp) 
			RETURNING "acquaintances"."id" INTO "Id";
 	RETURN QUERY SELECT "acquaintances"."id",
						"acquaintances"."documentId",
						"acquaintances"."userId",
						"acquaintances"."initiatorId",
						"acquaintances"."seen_at",
						"acquaintances"."created_at",
						"acquaintances"."updated_at",
						"acquaintances"."removed"
				   FROM "acquaintances"
				  WHERE "acquaintances"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddNewAcquaintance"("FltDocumentId" bigint, "FltUserId" bigint, "FltInitiatorId" bigint) OWNER TO postgres;

--
-- TOC entry 300 (class 1255 OID 117937)
-- Name: AddNewAgreement(bigint, timestamp without time zone); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddNewAgreement"("FltDocId" bigint, "FltDeadline" timestamp without time zone) RETURNS TABLE(id bigint, "documentId" bigint, agreed_at timestamp without time zone, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, deadline timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	INSERT INTO "agreements" (
						"documentId", 
						"created_at",
						"deadline"
						)
  			VALUES ("FltDocId", 
					current_timestamp,
				    "FltDeadline") 
			RETURNING "agreements"."id" INTO "Id";
	RETURN QUERY SELECT "agreements"."id",
						"agreements"."documentId",
						"agreements"."agreed_at",
						"agreements"."created_at",
						"agreements"."updated_at",
						"agreements"."removed",
						"agreements"."refused_at",
						"agreements"."deadline"
			   FROM "agreements"
			  WHERE "agreements"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddNewAgreement"("FltDocId" bigint, "FltDeadline" timestamp without time zone) OWNER TO postgres;

--
-- TOC entry 301 (class 1255 OID 117938)
-- Name: AddNewAssignment(bigint, smallint, bigint, character varying, bigint, bigint, bigint, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddNewAssignment"("FltDocumentId" bigint, "FltTypeId" smallint, "FltAuthorId" bigint, "FltText" character varying, "FltUserId" bigint, "FltBaseId" bigint, "FltMainId" bigint, "FltDescription" text) RETURNS TABLE(id bigint, "documentId" bigint, "typeId" smallint, "authorId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, text character varying, "executorId" bigint, "baseId" bigint, viewed_at timestamp without time zone, "mainId" bigint, description text)
    LANGUAGE plpgsql
    AS $$
DECLARE "Id" bigint; 
BEGIN
 	INSERT INTO "assignments" (
				 "documentId", 
				 "typeId", 
				 "authorId",
				 "text",
				 "created_at",
				 "executorId",
				 "baseId",
				 "mainId",
				 "description")
  			VALUES ("FltDocumentId", 
					"FltTypeId", 
					"FltAuthorId", 
					"FltText",
					current_timestamp,
					"FltUserId",
					"FltBaseId",
				   	"FltMainId",
				   	"FltDescription")
			RETURNING "assignments"."id" INTO "Id";
 	RETURN QUERY SELECT "assignments"."id",
						"assignments"."documentId",
						"assignments"."typeId",
						"assignments"."authorId",
						"assignments"."created_at",
						"assignments"."updated_at",
						"assignments"."removed",
					   	"assignments"."text",
					    "assignments"."executorId",
					   	"assignments"."baseId",
						"assignments"."viewed_at",
						"assignments"."mainId",
						"assignments"."description"
					FROM "assignments"
					WHERE "assignments"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddNewAssignment"("FltDocumentId" bigint, "FltTypeId" smallint, "FltAuthorId" bigint, "FltText" character varying, "FltUserId" bigint, "FltBaseId" bigint, "FltMainId" bigint, "FltDescription" text) OWNER TO postgres;

--
-- TOC entry 302 (class 1255 OID 117939)
-- Name: AddNewAssignmentDeadline(bigint, bigint, bigint, timestamp without time zone, timestamp without time zone, timestamp without time zone, character varying, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddNewAssignmentDeadline"("FltAssignmentId" bigint, "FltInitiatorId" bigint, "FltApprovedUserId" bigint, "FltDeadline" timestamp without time zone, "FltInitiatedAt" timestamp without time zone, "FltApprovedAt" timestamp without time zone, "FltComment" character varying, "FltFileId" bigint) RETURNS TABLE(id bigint, "assignmentId" bigint, "initiatorId" bigint, "approvedUserId" bigint, created_at timestamp without time zone, deadline timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, initiated_at timestamp without time zone, approved_at timestamp without time zone, refused_at timestamp without time zone, comment character varying, "fileId" bigint)
    LANGUAGE plpgsql
    AS $$
DECLARE "Id" bigint;
BEGIN
INSERT INTO "assignments_deadlines" (
				 "assignmentId", 
				 "initiatorId", 
				 "approvedUserId",
				 "created_at",
				 "deadline",
				 "initiated_at",
				 "approved_at",
				 "comment",
				 "fileId")
  			VALUES ("FltAssignmentId", 
					"FltInitiatorId", 
					"FltApprovedUserId", 
					current_timestamp,
					"FltDeadline",
					"FltInitiatedAt",
					"FltApprovedAt",
					"FltComment",
					"FltFileId"
				   )
	RETURNING "assignments_deadlines"."id" INTO "Id";
 	RETURN QUERY SELECT "assignments_deadlines"."id",
						"assignments_deadlines"."assignmentId",
						"assignments_deadlines"."initiatorId",
						"assignments_deadlines"."approvedUserId",
						"assignments_deadlines"."created_at",
						"assignments_deadlines"."deadline",
						"assignments_deadlines"."updated_at",
						"assignments_deadlines"."removed",
						"assignments_deadlines"."initiated_at",
						"assignments_deadlines"."approved_at",
						"assignments_deadlines"."refused_at",
						"assignments_deadlines"."comment",
						"assignments_deadlines"."fileId"
					FROM "assignments_deadlines"
					WHERE "assignments_deadlines"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddNewAssignmentDeadline"("FltAssignmentId" bigint, "FltInitiatorId" bigint, "FltApprovedUserId" bigint, "FltDeadline" timestamp without time zone, "FltInitiatedAt" timestamp without time zone, "FltApprovedAt" timestamp without time zone, "FltComment" character varying, "FltFileId" bigint) OWNER TO postgres;

--
-- TOC entry 303 (class 1255 OID 117940)
-- Name: AddNewBlogItem(character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddNewBlogItem"("FltTitle" character varying, "FltText" character varying) RETURNS TABLE(id bigint, title character varying, text character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	INSERT INTO "blog" ("title", 
						"text", 
						"created_at")
  			VALUES ("FltTitle", 
					"FltText", 
					current_timestamp) RETURNING "blog"."id" INTO "Id";
			RETURN QUERY SELECT  "blog"."id",
    							 "blog"."title",
								 "blog"."text",
								 "blog"."created_at",
								 "blog"."updated_at",
								 "blog"."removed"
						FROM "blog" WHERE "blog"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddNewBlogItem"("FltTitle" character varying, "FltText" character varying) OWNER TO postgres;

--
-- TOC entry 276 (class 1255 OID 117941)
-- Name: AddNewDiruser(character varying, character varying, character varying, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddNewDiruser"("FltSurname" character varying, "FltFirstname" character varying, "FltPatronymic" character varying, "FltDepartmentId" integer) RETURNS TABLE(id bigint, surname character varying, firstname character varying, patronymic character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, "departmentId" integer)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	INSERT INTO "dirusers" ("surname", 
						 "firstname", 
						 "patronymic", 
						 "created_at",
						 "departmentId")
  			VALUES ("FltSurname", 
					"FltFirstname", 
					"FltPatronymic",
					current_timestamp,
					"FltDepartmentId"
				   ) RETURNING "dirusers"."id" INTO "Id";
			RETURN QUERY SELECT  "dirusers"."id",
								 "dirusers"."surname",
								 "dirusers"."firstname",
								 "dirusers"."patronymic",
								 "dirusers"."created_at",
								 "dirusers"."updated_at",
								 "dirusers"."removed",
								 "dirusers"."departmentId"
						FROM "dirusers" WHERE "dirusers"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddNewDiruser"("FltSurname" character varying, "FltFirstname" character varying, "FltPatronymic" character varying, "FltDepartmentId" integer) OWNER TO postgres;

--
-- TOC entry 304 (class 1255 OID 117942)
-- Name: AddNewDocument(character varying, bigint, character varying, integer, character varying, smallint, bigint, bigint, bigint, bigint, smallint, character varying, timestamp without time zone, timestamp without time zone, character varying, character varying, character varying, text, character varying, character varying, timestamp without time zone, character varying, character varying, bigint, character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddNewDocument"("FltDesc" character varying, "FltAuthorId" bigint, "FltFile" character varying, "FltDepartmentId" integer, "FltOrderNum" character varying, "FltDeliveryId" smallint, "FltRecorderId" bigint, "FltBaseId" bigint, "FltBaseAssignmentId" bigint, "FltLinkedDocId" bigint, "FltTypeId" smallint, "FltName" character varying, "FltCreationDate" timestamp without time zone, "FltCloseDate" timestamp without time zone, "FltCoExecutor" character varying, "FltColName" character varying, "FltSumContract" character varying, "FltPhases" text, "FltNote" character varying, "FltAuthor" character varying, "FltAcqDate" timestamp without time zone, "FltCustomer" character varying, "FltAddresser" character varying, "FltExecutor" bigint, "FltSignatory" character varying, "FltLetterExecutor" character varying) RETURNS TABLE(id bigint, description character varying, "authorId" bigint, file character varying, created_at timestamp without time zone, updated_at timestamp without time zone, "departmentId" bigint, "orderNum" character varying, "deliveryId" smallint, "recorderId" bigint, "baseId" bigint, "baseAssignmentId" bigint, "linkedDocId" bigint, "typeId" smallint, removed timestamp without time zone, name character varying, "creationDate" timestamp without time zone, "closeDate" timestamp without time zone, "coExecutor" character varying, "colName" character varying, "sumContract" character varying, phases text, note character varying, author character varying, "acqDate" timestamp without time zone, customer character varying, addresser character varying, executor bigint, signatory character varying, "letterExecutor" character varying)
    LANGUAGE plpgsql
    AS $$
 DECLARE "Id" bigint;
 BEGIN
 	INSERT INTO "documents" ("description", 
						 "authorId", 
						 "file", 
						 "created_at", 
						 "departmentId", 
						 "orderNum", 
						 "deliveryId", 
						 "recorderId",
						 "baseId",
						 "baseAssignmentId",
						 "linkedDocId",
						 "typeId",
						 "name",
						 "creationDate",
						 "closeDate",
						 "coExecutor",
						 "colName",
						 "sumContract",
						 "phases",
						 "note",
						 "author",
						 "acqDate",
						 "executor",
						 "addresser",
						 "customer",
						 "signatory",
						 "letterExecutor"
			)
  			VALUES ("FltDesc", 
					"FltAuthorId", 
					"FltFile", 
					current_timestamp,
					"FltDepartmentId", 
					"FltOrderNum",
					"FltDeliveryId",
					"FltRecorderId",
				    "FltBaseId",
				    "FltBaseAssignmentId",
					"FltLinkedDocId",
					"FltTypeId",
					"FltName",
					"FltCreationDate",
					"FltCloseDate",
					"FltCoExecutor",
					"FltColName",
					"FltSumContract",
					"FltPhases",
					"FltNote",
					"FltAuthor",
					"FltAcqDate",
					"FltExecutor",
					"FltAddresser",
					"FltCustomer",
					"FltSignatory",
					"FltLetterExecutor"
				   ) 
			RETURNING "documents"."id" INTO "Id";
 	RETURN QUERY SELECT "documents"."id",
						"documents"."description",
						"documents"."authorId",
						"documents"."file",
						"documents"."created_at",
						"documents"."updated_at",
						"documents"."departmentId",
						"documents"."orderNum",
						"documents"."deliveryId",
						"documents"."recorderId",
						"documents"."baseId",
						"documents"."baseAssignmentId",
						"documents"."linkedDocId",
						"documents"."typeId",
						"documents"."removed",
						"documents"."name",
						"documents"."creationDate",
						"documents"."closeDate",
						"documents"."coExecutor",
						"documents"."colName",
						"documents"."sumContract",
						"documents"."phases",
						"documents"."note",
						"documents"."author",
						"documents"."acqDate",
						"documents"."customer",
						"documents"."addresser",
						"documents"."executor",
						"documents"."signatory",
						"documents"."letterExecutor"
				   FROM "documents" 
				  WHERE "documents"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddNewDocument"("FltDesc" character varying, "FltAuthorId" bigint, "FltFile" character varying, "FltDepartmentId" integer, "FltOrderNum" character varying, "FltDeliveryId" smallint, "FltRecorderId" bigint, "FltBaseId" bigint, "FltBaseAssignmentId" bigint, "FltLinkedDocId" bigint, "FltTypeId" smallint, "FltName" character varying, "FltCreationDate" timestamp without time zone, "FltCloseDate" timestamp without time zone, "FltCoExecutor" character varying, "FltColName" character varying, "FltSumContract" character varying, "FltPhases" text, "FltNote" character varying, "FltAuthor" character varying, "FltAcqDate" timestamp without time zone, "FltCustomer" character varying, "FltAddresser" character varying, "FltExecutor" bigint, "FltSignatory" character varying, "FltLetterExecutor" character varying) OWNER TO postgres;

--
-- TOC entry 305 (class 1255 OID 117943)
-- Name: AddNewDocumentStatus(bigint, smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddNewDocumentStatus"("FltDocumentId" bigint, "FltStatusId" smallint) RETURNS TABLE(id bigint, "documentId" bigint, "docstatusId" smallint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
DECLARE
	"Id" bigint;
 BEGIN
 	INSERT INTO "documents_and_docstatuses" ("documentId", 
						 "docstatusId", 
						 "created_at")
  			VALUES ("FltDocumentId", 
					"FltStatusId", 
					current_timestamp) 
			RETURNING "documents_and_docstatuses"."id" INTO "Id";
 	RETURN QUERY SELECT "documents_and_docstatuses"."id",
						"documents_and_docstatuses"."documentId",
						"documents_and_docstatuses"."docstatusId",
						"documents_and_docstatuses"."created_at",
						"documents_and_docstatuses"."updated_at",
						"documents_and_docstatuses"."removed"
				   FROM "documents_and_docstatuses" 
				  WHERE "documents_and_docstatuses"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddNewDocumentStatus"("FltDocumentId" bigint, "FltStatusId" smallint) OWNER TO postgres;

--
-- TOC entry 306 (class 1255 OID 117944)
-- Name: AddNewDocumentStatusByAgreementId(bigint, smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddNewDocumentStatusByAgreementId"("FltAgreementId" bigint, "FltStatusId" smallint, OUT "Id" bigint) RETURNS bigint
    LANGUAGE plpgsql
    AS $$
 DECLARE
 	"documentId" bigint;
 BEGIN
 	SELECT "agreements"."documentId" 
	  INTO "documentId" 
	  FROM "agreements"
	 WHERE "agreements"."id" = "FltAgreementId";
	IF ("documentId" IS NOT NULL) THEN
		BEGIN
			INSERT INTO "documents_and_docstatuses" ("documentId", 
						 "docstatusId", 
						 "created_at")
  			VALUES ("documentId", 
					"FltStatusId", 
					current_timestamp) 
			RETURNING "documents_and_docstatuses"."id" INTO "Id";
 			RETURN;
		END;
	END IF;
 END;
 $$;


ALTER FUNCTION public."AddNewDocumentStatusByAgreementId"("FltAgreementId" bigint, "FltStatusId" smallint, OUT "Id" bigint) OWNER TO postgres;

--
-- TOC entry 307 (class 1255 OID 117945)
-- Name: AddNewFile(character varying, character varying, smallint, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddNewFile"("FltFile" character varying, "FltFormat" character varying, "FltType" smallint, "FltComment" character varying) RETURNS TABLE(id bigint, file character varying, format character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, type smallint, comment character varying)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	INSERT INTO "files" (
						"file", 
						"format",
						"created_at",
						"type",
						"comment"
						)
  			VALUES ("FltFile",
					"FltFormat",
					current_timestamp,
				   	"FltType",
				   	"FltComment") 
			RETURNING "files"."id" INTO "Id";
 	RETURN QUERY SELECT "files"."id",
						"files"."file",
						"files"."format",
						"files"."created_at",
						"files"."updated_at",
						"files"."removed",
						"files"."type",
						"files"."comment"
				   FROM "files"
				  WHERE "files"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddNewFile"("FltFile" character varying, "FltFormat" character varying, "FltType" smallint, "FltComment" character varying) OWNER TO postgres;

--
-- TOC entry 308 (class 1255 OID 117946)
-- Name: AddNewMailsetting(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddNewMailsetting"("FltTitleId" character varying) RETURNS TABLE(id smallint, title character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	INSERT INTO "mailsettings" (
						"title", 
						"created_at"
						)
  			VALUES ("FltTitleId",
					current_timestamp) 
			RETURNING "mailsettings"."id" INTO "Id";
 	RETURN QUERY SELECT "mailsettings"."id",
						"mailsettings"."title",
						"mailsettings"."created_at",
						"mailsettings"."updated_at",
						"mailsettings"."removed"
				   FROM "mailsettings"
				  WHERE "mailsettings"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddNewMailsetting"("FltTitleId" character varying) OWNER TO postgres;

--
-- TOC entry 309 (class 1255 OID 117947)
-- Name: AddNewMailsettingUser(bigint, smallint, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddNewMailsettingUser"("FltUserId" bigint, "FltSettingId" smallint, "FltStatus" boolean) RETURNS TABLE(id bigint, "userId" bigint, "settingId" smallint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, status boolean)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	INSERT INTO "mailsettings_users" (
						"userId", 
						"settingId",
						"created_at",
						"status"
						)
  			VALUES ("FltUserId",
					"FltSettingId",
					current_timestamp,
				   	"FltStatus") 
			RETURNING "mailsettings_users"."id" INTO "Id";
 	RETURN QUERY SELECT "mailsettings_users"."id",
						"mailsettings_users"."userId",
						"mailsettings_users"."settingId",
						"mailsettings_users"."created_at",
						"mailsettings_users"."updated_at",
						"mailsettings_users"."removed",
						"mailsettings_users"."status"
				   FROM "mailsettings_users"
				  WHERE "mailsettings_users"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddNewMailsettingUser"("FltUserId" bigint, "FltSettingId" smallint, "FltStatus" boolean) OWNER TO postgres;

--
-- TOC entry 310 (class 1255 OID 117948)
-- Name: AddNewUser(character varying, character varying, character varying, character varying, character varying, integer, character varying, smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddNewUser"("FltLogin" character varying, "FltSurname" character varying, "FltFirstname" character varying, "FltPatronymic" character varying, "FltEmail" character varying, "FltDepartment" integer, "FltPassword" character varying, "FltRoleId" smallint) RETURNS TABLE(id bigint, login character varying, surname character varying, firstname character varying, patronymic character varying, department integer, email character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, roleid smallint)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	INSERT INTO "users" ("login", 
						 "surname", 
						 "firstname", 
						 "patronymic", 
						 "department", 
						 "email", 
						 "email_verified_at", 
						 "password",
						 "created_at",
						 "roleid")
  			VALUES ("FltLogin", 
					"FltSurname", 
					"FltFirstname", 
					"FltPatronymic", 
					"FltDepartment",
					"FltEmail",
					current_timestamp,
					"FltPassword",
				    current_timestamp,
				    "FltRoleId") RETURNING "users"."id" INTO "Id";
			RETURN QUERY SELECT  "users"."id",
    							 "users"."login",
								 "users"."surname",
								 "users"."firstname",
								 "users"."patronymic",
								 "users"."department",
								 "users"."email",
								 "users"."created_at",
								 "users"."updated_at",
								 "users"."removed",
								 "users"."roleid"
						FROM "users" WHERE "users"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddNewUser"("FltLogin" character varying, "FltSurname" character varying, "FltFirstname" character varying, "FltPatronymic" character varying, "FltEmail" character varying, "FltDepartment" integer, "FltPassword" character varying, "FltRoleId" smallint) OWNER TO postgres;

--
-- TOC entry 311 (class 1255 OID 117949)
-- Name: AddUserAndDepartment(bigint, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."AddUserAndDepartment"("FltUserId" bigint, "FltDepartmentId" bigint) RETURNS TABLE(id bigint, "userId" bigint, "departmentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	INSERT INTO "users_and_departments" (
						"userId", 
						"departmentId",
						"created_at"
					)
  			VALUES ("FltUserId",
					"FltDepartmentId",
					current_timestamp) 
			RETURNING "users_and_departments"."id" INTO "Id";
 	RETURN QUERY SELECT "users_and_departments"."id",
						"users_and_departments"."userId",
						"users_and_departments"."departmentId",
						"users_and_departments"."created_at",
						"users_and_departments"."updated_at",
						"users_and_departments"."removed"
				   FROM "users_and_departments"
				  WHERE "users_and_departments"."id" = "Id";
 END
 $$;


ALTER FUNCTION public."AddUserAndDepartment"("FltUserId" bigint, "FltDepartmentId" bigint) OWNER TO postgres;

--
-- TOC entry 312 (class 1255 OID 117950)
-- Name: ApproveAgreement(bigint, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."ApproveAgreement"("FltId" bigint, "FltUserId" bigint, OUT "Result" bigint) RETURNS bigint
    LANGUAGE plpgsql
    AS $$
BEGIN
	IF EXISTS (SELECT * FROM "GetAgreementAndUserByUserId"("FltUserId") WHERE "agreementId" = "FltId" AND "removed" IS NULL AND "note" IS NULL ORDER BY "created_at" DESC LIMIT 1) THEN
		BEGIN
			UPDATE "agreements" 
				SET "agreed_at" = current_timestamp,
					"updated_at" = current_timestamp
				WHERE "id" = "FltId";
			"Result" = 1;	
			RETURN;
		END;
	END IF;
END
$$;


ALTER FUNCTION public."ApproveAgreement"("FltId" bigint, "FltUserId" bigint, OUT "Result" bigint) OWNER TO postgres;

--
-- TOC entry 313 (class 1255 OID 117951)
-- Name: ApproveAgreementById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."ApproveAgreementById"("FltAgreementId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, agreed_at timestamp without time zone, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, deadline timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "agreements" 
 				SET "agreed_at" = current_timestamp,
 					"updated_at" = current_timestamp,
					"refused_at" = NULL
 				WHERE "agreements"."id" = "FltAgreementId";
	RETURN QUERY SELECT "agreements"."id",
					"agreements"."documentId",
					"agreements"."agreed_at",
					"agreements"."created_at",
					"agreements"."updated_at",
					"agreements"."removed",
					"agreements"."refused_at",
					"agreements"."deadline"
			FROM "agreements" 
			WHERE "agreements"."id" = "FltAgreementId";
END
$$;


ALTER FUNCTION public."ApproveAgreementById"("FltAgreementId" bigint) OWNER TO postgres;

--
-- TOC entry 314 (class 1255 OID 117952)
-- Name: ApproveAgreementsAndUsersById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."ApproveAgreementsAndUsersById"("FltId" bigint) RETURNS TABLE(id bigint, "agreementId" bigint, "userId" bigint, note text, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, approved_at timestamp without time zone, viewed_at timestamp without time zone, "order" smallint)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "agreements_and_users" 
		SET "removed" = NULL, "updated_at" = current_timestamp, "refused_at" = NULL, "approved_at" = current_timestamp
		WHERE "agreements_and_users"."id" = "FltId";
		RETURN QUERY SELECT "agreements_and_users"."id",
							"agreements_and_users"."agreementId",
							"agreements_and_users"."userId",
							"agreements_and_users"."note",
							"agreements_and_users"."created_at",
							"agreements_and_users"."updated_at",
							"agreements_and_users"."removed", 
							"agreements_and_users"."refused_at",
							"agreements_and_users"."approved_at",
							"agreements_and_users"."viewed_at",
							"agreements_and_users"."order"
					FROM "agreements_and_users" 
				   WHERE "agreements_and_users"."id" = "FltId" ;
END
$$;


ALTER FUNCTION public."ApproveAgreementsAndUsersById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 315 (class 1255 OID 117953)
-- Name: ApproveAssignmentDeadline(bigint, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."ApproveAssignmentDeadline"("FltId" bigint, "FltApproverId" bigint) RETURNS TABLE(id bigint, "assignmentId" bigint, "initiatorId" bigint, "approvedUserId" bigint, created_at timestamp without time zone, deadline timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, initiated_at timestamp without time zone, approved_at timestamp without time zone, refused_at timestamp without time zone, comment character varying, "fileId" bigint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		UPDATE "assignments_deadlines" 
			SET "approved_at" = current_timestamp,
				"updated_at" = current_timestamp,
				"approvedUserId" = "FltApproverId"
		WHERE "assignments_deadlines"."id" = "FltId";
		RETURN QUERY SELECT "assignments_deadlines"."id",
							"assignments_deadlines"."assignmentId",
							"assignments_deadlines"."initiatorId",
							"assignments_deadlines"."approvedUserId",
							"assignments_deadlines"."created_at",
							"assignments_deadlines"."deadline",
							"assignments_deadlines"."updated_at",
							"assignments_deadlines"."removed",
							"assignments_deadlines"."initiated_at",
							"assignments_deadlines"."approved_at",
							"assignments_deadlines"."refused_at",
							"assignments_deadlines"."comment",
						"assignments_deadlines"."fileId"
					FROM "assignments_deadlines" WHERE "assignments_deadlines"."id" = "FltId";
	END;
 $$;


ALTER FUNCTION public."ApproveAssignmentDeadline"("FltId" bigint, "FltApproverId" bigint) OWNER TO postgres;

--
-- TOC entry 316 (class 1255 OID 117954)
-- Name: GetAcquaintanceByDocumentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAcquaintanceByDocumentId"("FltDocumentId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "userId" bigint, "initiatorId" bigint, seen_at timestamp without time zone, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "acquaintances"."id", 
							"acquaintances"."documentId",
							"acquaintances"."userId",
							"acquaintances"."initiatorId",
							"acquaintances"."seen_at",
							"acquaintances"."created_at",
							"acquaintances"."updated_at",					
							"acquaintances"."removed"
					FROM "acquaintances" 
					WHERE "acquaintances"."documentId" = "FltDocumentId";
	END;
	$$;


ALTER FUNCTION public."GetAcquaintanceByDocumentId"("FltDocumentId" bigint) OWNER TO postgres;

--
-- TOC entry 317 (class 1255 OID 117955)
-- Name: GetAcquaintanceById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAcquaintanceById"("FltId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "userId" bigint, "initiatorId" bigint, seen_at timestamp without time zone, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "acquaintances"."id", 
							"acquaintances"."documentId",
							"acquaintances"."userId",
							"acquaintances"."initiatorId",
							"acquaintances"."seen_at",
							"acquaintances"."created_at",
							"acquaintances"."updated_at",					
							"acquaintances"."removed"
					FROM "acquaintances" 
					WHERE "acquaintances"."id" = "FltId";
	END;
	$$;


ALTER FUNCTION public."GetAcquaintanceById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 318 (class 1255 OID 117956)
-- Name: GetAcquaintanceByInitiatorId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAcquaintanceByInitiatorId"("FltInitiatorId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "userId" bigint, "initiatorId" bigint, seen_at timestamp without time zone, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "acquaintances"."id", 
							"acquaintances"."documentId",
							"acquaintances"."userId",
							"acquaintances"."initiatorId",
							"acquaintances"."seen_at",
							"acquaintances"."created_at",
							"acquaintances"."updated_at",					
							"acquaintances"."removed"
					FROM "acquaintances" 
					WHERE "acquaintances"."initiatorId" = "FltInitiatorId";
	END;
	$$;


ALTER FUNCTION public."GetAcquaintanceByInitiatorId"("FltInitiatorId" bigint) OWNER TO postgres;

--
-- TOC entry 277 (class 1255 OID 117957)
-- Name: GetAcquaintancesByUserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAcquaintancesByUserId"("FltUserId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "userId" bigint, "initiatorId" bigint, seen_at timestamp without time zone, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "acquaintances"."id", 
							"acquaintances"."documentId",
							"acquaintances"."userId",
							"acquaintances"."initiatorId",
							"acquaintances"."seen_at",
							"acquaintances"."created_at",
							"acquaintances"."updated_at",					
							"acquaintances"."removed"
					FROM "acquaintances" 
					WHERE "acquaintances"."userId" = "FltUserId";
	END;
	$$;


ALTER FUNCTION public."GetAcquaintancesByUserId"("FltUserId" bigint) OWNER TO postgres;

--
-- TOC entry 319 (class 1255 OID 117958)
-- Name: GetAgreementAndUserByAgreementId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAgreementAndUserByAgreementId"("FltAgreementId" bigint) RETURNS TABLE(id bigint, "agreementId" bigint, "userId" bigint, note text, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, approved_at timestamp without time zone, viewed_at timestamp without time zone, "order" smallint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "agreements_and_users"."id",
							"agreements_and_users"."agreementId",
							"agreements_and_users"."userId",
							"agreements_and_users"."note",
							"agreements_and_users"."created_at",
							"agreements_and_users"."updated_at",
							"agreements_and_users"."removed",
							"agreements_and_users"."refused_at",
							"agreements_and_users"."approved_at",
							"agreements_and_users"."viewed_at",
							"agreements_and_users"."order"
					FROM "agreements_and_users" WHERE "agreements_and_users"."agreementId" = "FltAgreementId";
	END;
	$$;


ALTER FUNCTION public."GetAgreementAndUserByAgreementId"("FltAgreementId" bigint) OWNER TO postgres;

--
-- TOC entry 320 (class 1255 OID 117959)
-- Name: GetAgreementAndUserByUserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAgreementAndUserByUserId"("FltUserId" bigint) RETURNS TABLE(id bigint, "agreementId" bigint, "userId" bigint, note text, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, approved_at timestamp without time zone, viewed_at timestamp without time zone, "order" smallint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "agreements_and_users"."id",
							"agreements_and_users"."agreementId",
							"agreements_and_users"."userId",
							"agreements_and_users"."note",
							"agreements_and_users"."created_at",
							"agreements_and_users"."updated_at",
							"agreements_and_users"."removed",
							"agreements_and_users"."refused_at",
							"agreements_and_users"."approved_at",
							"agreements_and_users"."viewed_at",
							"agreements_and_users"."order"
					FROM "agreements_and_users" WHERE "agreements_and_users"."userId" = "FltUserId";
	END;
	$$;


ALTER FUNCTION public."GetAgreementAndUserByUserId"("FltUserId" bigint) OWNER TO postgres;

--
-- TOC entry 321 (class 1255 OID 117960)
-- Name: GetAgreementAndUserInOrder(bigint, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAgreementAndUserInOrder"("FltUserId" bigint, "FltAgreementId" bigint) RETURNS TABLE(id bigint, "agreementId" bigint, "userId" bigint, note text, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, approved_at timestamp without time zone, viewed_at timestamp without time zone, "order" smallint)
    LANGUAGE plpgsql
    AS $$
	DECLARE 
		"arr" record;
	BEGIN 	
		FOR "arr" IN (SELECT * FROM "GetAgreementAndUserByAgreementId"("FltAgreementId") AS "ga" WHERE "ga"."removed" IS NULL AND "ga"."refused_at" IS NULL AND "ga"."approved_at" IS NULL ORDER BY "ga"."order" ASC LIMIT 1) 
		LOOP
			BEGIN
				IF ("arr"."userId" = "FltUserId") THEN
					BEGIN
						RETURN QUERY SELECT * FROM "agreements_and_users" WHERE "agreements_and_users"."id" = "arr"."id";
					END;
				END IF;
			END;
		END LOOP;
	END;
	$$;


ALTER FUNCTION public."GetAgreementAndUserInOrder"("FltUserId" bigint, "FltAgreementId" bigint) OWNER TO postgres;

--
-- TOC entry 322 (class 1255 OID 117961)
-- Name: GetAgreementByDocumentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAgreementByDocumentId"("FltDocumentId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, agreed_at timestamp without time zone, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, deadline timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "agreements"."id",
							"agreements"."documentId",
							"agreements"."agreed_at",
							"agreements"."created_at",
							"agreements"."updated_at",
							"agreements"."removed",
							"agreements"."refused_at",
							"agreements"."deadline"
					FROM "agreements" WHERE "agreements"."documentId" = "FltDocumentId";
	END;
	$$;


ALTER FUNCTION public."GetAgreementByDocumentId"("FltDocumentId" bigint) OWNER TO postgres;

--
-- TOC entry 323 (class 1255 OID 117962)
-- Name: GetAgreementById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAgreementById"("FltId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, agreed_at timestamp without time zone, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, deadline timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "agreements"."id",
							"agreements"."documentId",
							"agreements"."agreed_at",
							"agreements"."created_at",
							"agreements"."updated_at",
							"agreements"."removed",
							"agreements"."refused_at",
							"agreements"."deadline"
					FROM "agreements" WHERE "agreements"."id" = "FltId";
	END;
	$$;


ALTER FUNCTION public."GetAgreementById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 324 (class 1255 OID 117963)
-- Name: GetAllAcquaintances(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAllAcquaintances"() RETURNS TABLE(id bigint, "documentId" bigint, "userId" bigint, "initiatorId" bigint, seen_at timestamp without time zone, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "acquaintances"."id", 
							"acquaintances"."documentId",
							"acquaintances"."userId",
							"acquaintances"."initiatorId",
							"acquaintances"."seen_at",
							"acquaintances"."created_at",
							"acquaintances"."updated_at",					
							"acquaintances"."removed"
					FROM "acquaintances";
	END;
	$$;


ALTER FUNCTION public."GetAllAcquaintances"() OWNER TO postgres;

--
-- TOC entry 325 (class 1255 OID 117964)
-- Name: GetAllAgreements(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAllAgreements"() RETURNS TABLE(id bigint, "documentId" bigint, agreed_at timestamp without time zone, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, deadline timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "agreements"."id",
							"agreements"."documentId",
							"agreements"."agreed_at",
							"agreements"."created_at",
							"agreements"."updated_at",
							"agreements"."removed",
							"agreements"."refused_at",
							"agreements"."deadline"
					FROM "agreements";
	END;
	$$;


ALTER FUNCTION public."GetAllAgreements"() OWNER TO postgres;

--
-- TOC entry 326 (class 1255 OID 117965)
-- Name: GetAllAssignments(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAllAssignments"() RETURNS TABLE(id bigint, "documentId" bigint, "typeId" smallint, "authorId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, text character varying, "executorId" bigint, "baseId" bigint, viewed_at timestamp without time zone, "mainId" bigint, description text)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "assignments"."id",
							"assignments"."documentId",
							"assignments"."typeId",
							"assignments"."authorId",
							"assignments"."created_at",
							"assignments"."updated_at",
							"assignments"."removed",
							"assignments"."text",
							"assignments"."executorId",
							"assignments"."baseId",
							"assignments"."viewed_at",
							"assignments"."mainId",
							"assignments"."description"
					FROM "assignments";
	END;
	$$;


ALTER FUNCTION public."GetAllAssignments"() OWNER TO postgres;

--
-- TOC entry 327 (class 1255 OID 117966)
-- Name: GetAllDepartments(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAllDepartments"() RETURNS TABLE(id integer, code character varying, title character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, "headId" bigint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "departments"."id", 
							"departments"."code", 
							"departments"."title",
							"departments"."created_at",
							"departments"."updated_at",
							"departments"."removed",
							"departments"."headId"
					FROM "departments";
	END;
	$$;


ALTER FUNCTION public."GetAllDepartments"() OWNER TO postgres;

--
-- TOC entry 290 (class 1255 OID 117967)
-- Name: GetAllDirusers(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAllDirusers"() RETURNS TABLE(id bigint, surname character varying, firstname character varying, patronymic character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, "departmentId" integer)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "dirusers"."id", 
							"dirusers"."surname",
							"dirusers"."firstname",
							"dirusers"."patronymic",
							"dirusers"."created_at",
							"dirusers"."updated_at",
							"dirusers"."removed",
							"dirusers"."departmentId"
					FROM "dirusers";
	END;
	$$;


ALTER FUNCTION public."GetAllDirusers"() OWNER TO postgres;

--
-- TOC entry 328 (class 1255 OID 117968)
-- Name: GetAllDocuments(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAllDocuments"() RETURNS TABLE(id bigint, description character varying, "authorId" bigint, file character varying, created_at timestamp without time zone, updated_at timestamp without time zone, "departmentId" bigint, "orderNum" character varying, "deliveryId" smallint, "recorderId" bigint, "baseId" bigint, "baseAssignmentId" bigint, "linkedDocId" bigint, "typeId" smallint, removed timestamp without time zone, name character varying, "creationDate" timestamp without time zone, "closeDate" timestamp without time zone, "coExecutor" character varying, "colName" character varying, "sumContract" character varying, phases text, note character varying, author character varying, "acqDate" timestamp without time zone, customer character varying, addresser character varying, executor bigint, signatory character varying, "letterExecutor" character varying)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		 	RETURN QUERY SELECT "documents"."id",
						"documents"."description",
						"documents"."authorId",
						"documents"."file",
						"documents"."created_at",
						"documents"."updated_at",
						"documents"."departmentId",
						"documents"."orderNum",
						"documents"."deliveryId",
						"documents"."recorderId",
						"documents"."baseId",
						"documents"."baseAssignmentId",
						"documents"."linkedDocId",
						"documents"."typeId",
						"documents"."removed",
						"documents"."name",
						"documents"."creationDate",
						"documents"."closeDate",
						"documents"."coExecutor",
						"documents"."colName",
						"documents"."sumContract",
						"documents"."phases",
						"documents"."note",
						"documents"."author",
						"documents"."acqDate",
						"documents"."customer",
						"documents"."addresser",
						"documents"."executor",
						"documents"."signatory",
						"documents"."letterExecutor"
					FROM "documents";
	END;
	$$;


ALTER FUNCTION public."GetAllDocuments"() OWNER TO postgres;

--
-- TOC entry 329 (class 1255 OID 117969)
-- Name: GetAllMailsettings(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAllMailsettings"() RETURNS TABLE(id smallint, title character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "mailsettings"."id",
						"mailsettings"."title",
						"mailsettings"."created_at",
						"mailsettings"."updated_at",
						"mailsettings"."removed"
				FROM "mailsettings";
	END;
 $$;


ALTER FUNCTION public."GetAllMailsettings"() OWNER TO postgres;

--
-- TOC entry 330 (class 1255 OID 117970)
-- Name: GetAllStatuses(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAllStatuses"() RETURNS TABLE(id smallint, title character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, alias character varying, "group" smallint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "statuses"."id",
					"statuses"."title",
					"statuses"."created_at",
					"statuses"."updated_at",
					"statuses"."removed",
					"statuses"."alias",
					"statuses"."group"
			FROM "statuses";
	END;
$$;


ALTER FUNCTION public."GetAllStatuses"() OWNER TO postgres;

--
-- TOC entry 331 (class 1255 OID 117971)
-- Name: GetAllUsers(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAllUsers"() RETURNS TABLE(id bigint, login character varying, surname character varying, firstname character varying, patronymic character varying, department integer, email character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, roleid smallint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "users"."id", 
							"users"."login",
							"users"."surname",
							"users"."firstname",
							"users"."patronymic",
							"users"."department",
							"users"."email",
							"users"."created_at",
							"users"."updated_at",
							"users"."removed",
							"users"."roleid"
					FROM "users";
	END;
	$$;


ALTER FUNCTION public."GetAllUsers"() OWNER TO postgres;

--
-- TOC entry 332 (class 1255 OID 117972)
-- Name: GetAssignmentAndAssigmnentStatusesByAssignmentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAssignmentAndAssigmnentStatusesByAssignmentId"("FltAssignmentId" bigint) RETURNS TABLE(id bigint, "assignmentId" bigint, "assignmentstatusId" smallint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, note character varying)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "assignments_and_assignmentstatuses"."id",
							"assignments_and_assignmentstatuses"."assignmentId",
							"assignments_and_assignmentstatuses"."assignmentstatusId",
							"assignments_and_assignmentstatuses"."created_at",
							"assignments_and_assignmentstatuses"."updated_at",
							"assignments_and_assignmentstatuses"."removed",
							"assignments_and_assignmentstatuses"."note"
					FROM "assignments_and_assignmentstatuses"
					WHERE "assignments_and_assignmentstatuses"."assignmentId" = "FltAssignmentId";
	END;
	$$;


ALTER FUNCTION public."GetAssignmentAndAssigmnentStatusesByAssignmentId"("FltAssignmentId" bigint) OWNER TO postgres;

--
-- TOC entry 333 (class 1255 OID 117973)
-- Name: GetAssignmentById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAssignmentById"("FltId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "typeId" smallint, "authorId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, text character varying, "executorId" bigint, "baseId" bigint, viewed_at timestamp without time zone, "mainId" bigint, description text)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "assignments"."id",
							"assignments"."documentId",
							"assignments"."typeId",
							"assignments"."authorId",
							"assignments"."created_at",
							"assignments"."updated_at",
							"assignments"."removed",
							"assignments"."text",
							"assignments"."executorId",
							"assignments"."baseId",
							"assignments"."viewed_at",
							"assignments"."mainId",
							"assignments"."description"
					FROM "assignments" WHERE "assignments"."id" = "FltId";
	END;
 $$;


ALTER FUNCTION public."GetAssignmentById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 334 (class 1255 OID 117974)
-- Name: GetAssignmentControlByUserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAssignmentControlByUserId"("FltUserId" bigint) RETURNS TABLE(id bigint, "userId" bigint, "assignmentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, viewed_at timestamp without time zone, "initiatorId" bigint, "documentId" bigint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "controls"."id",
							"controls"."userId",
							"controls"."assignmentId",
							"controls"."created_at",
							"controls"."updated_at",
							"controls"."removed",
							"controls"."viewed_at",
							"controls"."initiatorId",
							"controls"."documentId"
					   FROM "controls"
					  WHERE "controls"."userId" = "FltUserId" AND "controls"."assignmentId" IS NOT NULL;
	END;
 $$;


ALTER FUNCTION public."GetAssignmentControlByUserId"("FltUserId" bigint) OWNER TO postgres;

--
-- TOC entry 335 (class 1255 OID 117975)
-- Name: GetAssignmentDeadlinesByAssignmentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAssignmentDeadlinesByAssignmentId"("FltAssignmentId" bigint) RETURNS TABLE(id bigint, "assignmentId" bigint, "initiatorId" bigint, "approvedUserId" bigint, created_at timestamp without time zone, deadline timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, initiated_at timestamp without time zone, approved_at timestamp without time zone, refused_at timestamp without time zone, comment character varying, "fileId" bigint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "assignments_deadlines"."id",
							"assignments_deadlines"."assignmentId",
							"assignments_deadlines"."initiatorId",
							"assignments_deadlines"."approvedUserId",
							"assignments_deadlines"."created_at",
							"assignments_deadlines"."deadline",
							"assignments_deadlines"."updated_at",
							"assignments_deadlines"."removed",
							"assignments_deadlines"."initiated_at",
							"assignments_deadlines"."approved_at",
							"assignments_deadlines"."refused_at",
							"assignments_deadlines"."comment",
						"assignments_deadlines"."fileId"
					FROM "assignments_deadlines" WHERE "assignments_deadlines"."assignmentId" = "FltAssignmentId";
	END;
 $$;


ALTER FUNCTION public."GetAssignmentDeadlinesByAssignmentId"("FltAssignmentId" bigint) OWNER TO postgres;

--
-- TOC entry 274 (class 1255 OID 117976)
-- Name: GetAssignmentTypeById(smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAssignmentTypeById"("FltTypeId" smallint) RETURNS TABLE(id smallint, title character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "assignment_types"."id",
							"assignment_types"."title",
							"assignment_types"."created_at",
							"assignment_types"."updated_at",
							"assignment_types"."removed"
					FROM "assignment_types" WHERE "assignment_types"."id" = "FltTypeId";
	END;
	$$;


ALTER FUNCTION public."GetAssignmentTypeById"("FltTypeId" smallint) OWNER TO postgres;

--
-- TOC entry 275 (class 1255 OID 117977)
-- Name: GetAssignmentTypes(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAssignmentTypes"() RETURNS TABLE(id smallint, title character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "assignment_types"."id", 
							"assignment_types"."title",
							"assignment_types"."created_at",
							"assignment_types"."updated_at",
							"assignment_types"."removed"
					FROM "assignment_types";
	END;
	$$;


ALTER FUNCTION public."GetAssignmentTypes"() OWNER TO postgres;

--
-- TOC entry 336 (class 1255 OID 117978)
-- Name: GetAssignmentsByAuthorId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAssignmentsByAuthorId"("FltAuthorId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "typeId" smallint, "authorId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, text character varying, "executorId" bigint, "baseId" bigint, viewed_at timestamp without time zone, "mainId" bigint, description text)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "assignments"."id",
							"assignments"."documentId",
							"assignments"."typeId",
							"assignments"."authorId",
							"assignments"."created_at",
							"assignments"."updated_at",
							"assignments"."removed",
							"assignments"."text",
							"assignments"."executorId",
							"assignments"."baseId",
							"assignments"."viewed_at",
							"assignments"."mainId",
							"assignments"."description"
					FROM "assignments"
					WHERE "assignments"."authorId" = "FltAuthorId";
	END;
	$$;


ALTER FUNCTION public."GetAssignmentsByAuthorId"("FltAuthorId" bigint) OWNER TO postgres;

--
-- TOC entry 337 (class 1255 OID 117979)
-- Name: GetAssignmentsByCreatedAtPeriod(timestamp without time zone, timestamp without time zone); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAssignmentsByCreatedAtPeriod"("FltCreatedAtFrom" timestamp without time zone, "FltCreatedAtTo" timestamp without time zone) RETURNS TABLE(id bigint, "documentId" bigint, "typeId" smallint, "authorId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, text character varying, "executorId" bigint, "baseId" bigint, viewed_at timestamp without time zone, "mainId" bigint, description text)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "assignments"."id",
							"assignments"."documentId",
							"assignments"."typeId",
							"assignments"."authorId",
							"assignments"."created_at",
							"assignments"."updated_at",
							"assignments"."removed",
							"assignments"."text",
							"assignments"."executorId",
							"assignments"."baseId",
							"assignments"."viewed_at",
							"assignments"."mainId",
							"assignments"."description"
					FROM "assignments"
					WHERE "assignments"."created_at" >= "FltCreatedAtFrom" AND "assignments"."created_at" <= "FltCreatedAtTo";
	END;
	$$;


ALTER FUNCTION public."GetAssignmentsByCreatedAtPeriod"("FltCreatedAtFrom" timestamp without time zone, "FltCreatedAtTo" timestamp without time zone) OWNER TO postgres;

--
-- TOC entry 338 (class 1255 OID 117980)
-- Name: GetAssignmentsByExecutorId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAssignmentsByExecutorId"("FltUserId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "typeId" smallint, "authorId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, text character varying, "executorId" bigint, "baseId" bigint, viewed_at timestamp without time zone, "mainId" bigint, description text)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "assignments"."id",
							"assignments"."documentId",
							"assignments"."typeId",
							"assignments"."authorId",
							"assignments"."created_at",
							"assignments"."updated_at",
							"assignments"."removed",
							"assignments"."text",
							"assignments"."executorId",
							"assignments"."baseId",
							"assignments"."viewed_at",
							"assignments"."mainId",
							"assignments"."description"
					FROM "assignments"
					WHERE "assignments"."executorId" = "FltUserId";
	END;
	$$;


ALTER FUNCTION public."GetAssignmentsByExecutorId"("FltUserId" bigint) OWNER TO postgres;

--
-- TOC entry 339 (class 1255 OID 117981)
-- Name: GetAssignmentsByMainId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAssignmentsByMainId"("FltId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "typeId" smallint, "authorId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, text character varying, "executorId" bigint, "baseId" bigint, viewed_at timestamp without time zone, "mainId" bigint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "assignments"."id",
							"assignments"."documentId",
							"assignments"."typeId",
							"assignments"."authorId",
							"assignments"."created_at",
							"assignments"."updated_at",
							"assignments"."removed",
							"assignments"."text",
							"assignments"."executorId",
							"assignments"."baseId",
							"assignments"."viewed_at",
							"assignments"."mainId"
					FROM "assignments"
					WHERE "assignments"."mainId" = "FltId";
	END;
	$$;


ALTER FUNCTION public."GetAssignmentsByMainId"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 340 (class 1255 OID 117982)
-- Name: GetAssignmentsByString(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetAssignmentsByString"("FltString" character varying) RETURNS TABLE(id bigint, "documentId" bigint, "typeId" smallint, "authorId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, text character varying, "executorId" bigint, "baseId" bigint, viewed_at timestamp without time zone, "mainId" bigint, description text)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "assignments"."id",
							"assignments"."documentId",
							"assignments"."typeId",
							"assignments"."authorId",
							"assignments"."created_at",
							"assignments"."updated_at",
							"assignments"."removed",
							"assignments"."text",
							"assignments"."executorId",
							"assignments"."baseId",
							"assignments"."viewed_at",
							"assignments"."mainId",
							"assignments"."description"
					FROM "assignments"
					WHERE LOWER("assignments"."text") SIMILAR TO LOWER(CONCAT('%', "FltString", '%')) 
					   OR LOWER("assignments"."description") SIMILAR TO LOWER(concat('%', "FltString", '%'));
	END;
	$$;


ALTER FUNCTION public."GetAssignmentsByString"("FltString" character varying) OWNER TO postgres;

--
-- TOC entry 341 (class 1255 OID 117983)
-- Name: GetBlog(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetBlog"() RETURNS TABLE(id bigint, title character varying, text character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "blog"."id",
							"blog"."title",
							"blog"."text",
							"blog"."created_at",
							"blog"."updated_at",
							"blog"."removed"
					FROM "blog";
	END;
	$$;


ALTER FUNCTION public."GetBlog"() OWNER TO postgres;

--
-- TOC entry 342 (class 1255 OID 117984)
-- Name: GetControlByAssignmentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetControlByAssignmentId"("FltAssignmentId" bigint) RETURNS TABLE(id bigint, "userId" bigint, "assignmentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, viewed_at timestamp without time zone, "initiatorId" bigint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "controls"."id",
							"controls"."userId",
							"controls"."assignmentId",
							"controls"."created_at",
							"controls"."updated_at",
							"controls"."removed",
							"controls"."viewed_at",
							"controls"."initiatorId"
					   FROM "controls"
					  WHERE "controls"."assignmentId" = "FltAssignmentId";
	END;
 $$;


ALTER FUNCTION public."GetControlByAssignmentId"("FltAssignmentId" bigint) OWNER TO postgres;

--
-- TOC entry 343 (class 1255 OID 117985)
-- Name: GetControlById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetControlById"("FltId" bigint) RETURNS TABLE(id bigint, "userId" bigint, "assignmentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, viewed_at timestamp without time zone, "initiatorId" bigint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "controls"."id",
							"controls"."userId",
							"controls"."assignmentId",
							"controls"."created_at",
							"controls"."updated_at",
							"controls"."removed",
							"controls"."viewed_at",
							"controls"."initiatorId"
					   FROM "controls"
					  WHERE "controls"."id" = "FltId";
	END;
 $$;


ALTER FUNCTION public."GetControlById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 344 (class 1255 OID 117986)
-- Name: GetControlByUserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetControlByUserId"("FltUserId" bigint) RETURNS TABLE(id bigint, "userId" bigint, "assignmentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, viewed_at timestamp without time zone, "initiatorId" bigint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "controls"."id",
							"controls"."userId",
							"controls"."assignmentId",
							"controls"."created_at",
							"controls"."updated_at",
							"controls"."removed",
							"controls"."viewed_at",
							"controls"."initiatorId"
					   FROM "controls"
					  WHERE "controls"."userId" = "FltUserId";
	END;
 $$;


ALTER FUNCTION public."GetControlByUserId"("FltUserId" bigint) OWNER TO postgres;

--
-- TOC entry 345 (class 1255 OID 117987)
-- Name: GetDeliveryTypes(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDeliveryTypes"() RETURNS TABLE(id smallint, title character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "delivery_types"."id", 
							"delivery_types"."title",
							"delivery_types"."created_at",
							"delivery_types"."updated_at",
							"delivery_types"."removed"
					FROM "delivery_types";
	END;
	$$;


ALTER FUNCTION public."GetDeliveryTypes"() OWNER TO postgres;

--
-- TOC entry 346 (class 1255 OID 117988)
-- Name: GetDepartmentById(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDepartmentById"("FltId" integer) RETURNS TABLE(id integer, code character varying, title character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, "headId" bigint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "departments"."id", 
							"departments"."code",
							"departments"."title",
							"departments"."created_at",
							"departments"."updated_at",
							"departments"."removed",
							"departments"."headId"
					FROM "departments" 
					WHERE "departments"."id" = "FltId";
	END;
	$$;


ALTER FUNCTION public."GetDepartmentById"("FltId" integer) OWNER TO postgres;

--
-- TOC entry 347 (class 1255 OID 117989)
-- Name: GetDiruserAndDocumentByDiruserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDiruserAndDocumentByDiruserId"("FltDiruserId" bigint) RETURNS TABLE(id bigint, "diruserId" bigint, "documentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "dirusers_and_documents"."id", 
							"dirusers_and_documents"."diruserId",
							"dirusers_and_documents"."documentId",
							"dirusers_and_documents"."created_at",
							"dirusers_and_documents"."updated_at",
							"dirusers_and_documents"."removed"
					FROM "dirusers_and_documents"
					WHERE "dirusers_and_documents"."diruserId" = "FltDiruserId";
	END;
	$$;


ALTER FUNCTION public."GetDiruserAndDocumentByDiruserId"("FltDiruserId" bigint) OWNER TO postgres;

--
-- TOC entry 348 (class 1255 OID 117990)
-- Name: GetDiruserAndDocumentByDocumentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDiruserAndDocumentByDocumentId"("FltDocId" bigint) RETURNS TABLE(id bigint, "diruserId" bigint, "documentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "dirusers_and_documents"."id", 
							"dirusers_and_documents"."diruserId",
							"dirusers_and_documents"."documentId",
							"dirusers_and_documents"."created_at",
							"dirusers_and_documents"."updated_at",
							"dirusers_and_documents"."removed"
					FROM "dirusers_and_documents"
					WHERE "dirusers_and_documents"."documentId" = "FltDocId";
	END;
	$$;


ALTER FUNCTION public."GetDiruserAndDocumentByDocumentId"("FltDocId" bigint) OWNER TO postgres;

--
-- TOC entry 349 (class 1255 OID 117991)
-- Name: GetDiruserAndDocumentById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDiruserAndDocumentById"("FltId" bigint) RETURNS TABLE(id bigint, "diruserId" bigint, "documentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "dirusers_and_documents"."id", 
							"dirusers_and_documents"."diruserId",
							"dirusers_and_documents"."documentId",
							"dirusers_and_documents"."created_at",
							"dirusers_and_documents"."updated_at",
							"dirusers_and_documents"."removed"
					FROM "dirusers_and_documents"
					WHERE "dirusers_and_documents"."id" = "FltId";
	END;
	$$;


ALTER FUNCTION public."GetDiruserAndDocumentById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 350 (class 1255 OID 117992)
-- Name: GetDiruserById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDiruserById"("FltId" bigint) RETURNS TABLE(id bigint, surname character varying, firstname character varying, patronymic character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, "departmentId" integer)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "dirusers"."id", 
							"dirusers"."surname",
							"dirusers"."firstname",
							"dirusers"."patronymic",
							"dirusers"."created_at",
							"dirusers"."updated_at",
							"dirusers"."removed",
							"dirusers"."departmentId"
					FROM "dirusers" 
					WHERE "dirusers"."id" = "FltId";
	END;
	$$;


ALTER FUNCTION public."GetDiruserById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 351 (class 1255 OID 117993)
-- Name: GetDocStatusLogByDocumentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDocStatusLogByDocumentId"("FltDocumentId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "docstatusId" smallint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, "docStatusTitle" character varying, "docStatusAlias" character varying)
    LANGUAGE plpgsql
    AS $$	DECLARE
		"arr" record;
	BEGIN 		
		FOR "arr" IN (SELECT "documents_and_docstatuses"."id",
							"documents_and_docstatuses"."documentId",
							"documents_and_docstatuses"."docstatusId",
							"documents_and_docstatuses"."created_at",
							"documents_and_docstatuses"."updated_at",
							"documents_and_docstatuses"."removed"
					   FROM "documents_and_docstatuses" 
					  WHERE "documents_and_docstatuses"."documentId" = "FltDocumentId"
					  ORDER BY "documents_and_docstatuses"."created_at" DESC
					 )
		LOOP
			BEGIN
				"id" := "arr"."id";
				"documentId" := "arr"."documentId";
				"docstatusId" := "arr"."docstatusId";
				"created_at" := "arr"."created_at";
				"updated_at" := "arr"."updated_at";
				"removed" := "arr"."removed";
				SELECT "GDSBY"."title",
					   "GDSBY"."alias"
				  INTO "docStatusTitle",
				       "docStatusAlias"
				  FROM "GetStatusById"("arr"."docstatusId") AS "GDSBY"
				 WHERE "GDSBY"."removed" IS NULL;
				RETURN NEXT;
			END;
		END LOOP;	
	END;
$$;


ALTER FUNCTION public."GetDocStatusLogByDocumentId"("FltDocumentId" bigint) OWNER TO postgres;

--
-- TOC entry 352 (class 1255 OID 117994)
-- Name: GetDocTypeById(smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDocTypeById"("FltId" smallint) RETURNS TABLE(id smallint, title character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "document_types"."id", 
							"document_types"."title",
							"document_types"."created_at",
							"document_types"."updated_at",
							"document_types"."removed"
					FROM "document_types" 
					WHERE "document_types"."id" = "FltId";
	END;
	$$;


ALTER FUNCTION public."GetDocTypeById"("FltId" smallint) OWNER TO postgres;

--
-- TOC entry 354 (class 1255 OID 117995)
-- Name: GetDocsByTypeId(smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDocsByTypeId"("FltTypeId" smallint) RETURNS TABLE(id bigint, description character varying, "authorId" bigint, file character varying, created_at timestamp without time zone, updated_at timestamp without time zone, "departmentId" bigint, "orderNum" character varying, "deliveryId" smallint, "recorderId" bigint, "baseId" bigint, "baseAssignmentId" bigint, "linkedDocId" bigint, "typeId" smallint, removed timestamp without time zone, name character varying, "creationDate" timestamp without time zone, "closeDate" timestamp without time zone, "coExecutor" character varying, "colName" character varying, "sumContract" character varying, phases text, note character varying, author character varying, "acqDate" timestamp without time zone, customer character varying, addresser character varying, executor bigint, signatory character varying, "letterExecutor" character varying)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
			RETURN QUERY SELECT "documents"."id",
						"documents"."description",
						"documents"."authorId",
						"documents"."file",
						"documents"."created_at",
						"documents"."updated_at",
						"documents"."departmentId",
						"documents"."orderNum",
						"documents"."deliveryId",
						"documents"."recorderId",
						"documents"."baseId",
						"documents"."baseAssignmentId",
						"documents"."linkedDocId",
						"documents"."typeId",
						"documents"."removed",
						"documents"."name",
						"documents"."creationDate",
						"documents"."closeDate",
						"documents"."coExecutor",
						"documents"."colName",
						"documents"."sumContract",
						"documents"."phases",
						"documents"."note",
						"documents"."author",
						"documents"."acqDate",
						"documents"."customer",
						"documents"."addresser",
						"documents"."executor",
						"documents"."signatory",
						"documents"."letterExecutor"
					FROM "documents" WHERE "documents"."typeId" = "FltTypeId";
	END;
	$$;


ALTER FUNCTION public."GetDocsByTypeId"("FltTypeId" smallint) OWNER TO postgres;

--
-- TOC entry 355 (class 1255 OID 117996)
-- Name: GetDocsByUserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDocsByUserId"("FltUserId" bigint) RETURNS TABLE(id bigint, description character varying, "authorId" bigint, file character varying, created_at timestamp without time zone, updated_at timestamp without time zone, "departmentId" bigint, "orderNum" character varying, "deliveryId" smallint, "recorderId" bigint, "baseId" bigint, "baseAssignmentId" bigint, "linkedDocId" bigint, "typeId" smallint, removed timestamp without time zone, name character varying, "creationDate" timestamp without time zone, "closeDate" timestamp without time zone, "coExecutor" character varying, "colName" character varying, "sumContract" character varying, phases text, note character varying, author character varying, "acqDate" timestamp without time zone, customer character varying, addresser character varying, executor bigint, signatory character varying, "letterExecutor" character varying)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
				RETURN QUERY SELECT "documents"."id",
						"documents"."description",
						"documents"."authorId",
						"documents"."file",
						"documents"."created_at",
						"documents"."updated_at",
						"documents"."departmentId",
						"documents"."orderNum",
						"documents"."deliveryId",
						"documents"."recorderId",
						"documents"."baseId",
						"documents"."baseAssignmentId",
						"documents"."linkedDocId",
						"documents"."typeId",
						"documents"."removed",
						"documents"."name",
						"documents"."creationDate",
						"documents"."closeDate",
						"documents"."coExecutor",
						"documents"."colName",
						"documents"."sumContract",
						"documents"."phases",
						"documents"."note",
						"documents"."author",
						"documents"."acqDate",
						"documents"."customer",
						"documents"."addresser",
						"documents"."executor",
						"documents"."signatory",
						"documents"."letterExecutor"
					FROM "documents" WHERE "documents"."authorId" = "FltUserId";
	END;
	$$;


ALTER FUNCTION public."GetDocsByUserId"("FltUserId" bigint) OWNER TO postgres;

--
-- TOC entry 356 (class 1255 OID 117997)
-- Name: GetDocsWithInfoByUserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDocsWithInfoByUserId"("FltUserId" bigint) RETURNS TABLE(id bigint, description character varying, "authorId" bigint, file character varying, created_at timestamp without time zone, updated_at timestamp without time zone, "departmentId" bigint, "orderNum" integer, "deliveryId" smallint, "recorderId" bigint, "baseId" bigint, "baseResolutionId" bigint, "linkedDocId" bigint, "typeId" smallint, removed timestamp without time zone, "documentType" character varying, surname character varying, firstname character varying, patronymic character varying)
    LANGUAGE plpgsql
    AS $$
	DECLARE
		"arr" record;
	BEGIN 	
		FOR "arr" IN (SELECT * FROM "GetDocsByUserId"("FltUserId")) LOOP
			BEGIN
				"id" := "arr"."id";
				"description" := "arr"."description";
				"authorId" := "arr"."authorId";
				"file" := "arr"."file";
				"created_at" := "arr"."created_at";
				"updated_at" := "arr"."updated_at";
				"departmentId" := "arr"."departmentId";
				"orderNum" := "arr"."orderNum";
				"deliveryId" := "arr"."deliveryId";
				"recorderId" := "arr"."recorderId";
				"baseId" := "arr"."baseId";
				"baseResolutionId" := "arr"."baseResolutionId";
				"linkedDocId" := "arr"."linkedDocId";
				"typeId" := "arr"."typeId";
				"removed" := "arr"."removed";
				
				SELECT "GDTBI"."title"
				  INTO "documentType"
				  FROM "GetDocTypeById"("arr"."typeId")
				    AS "GDTBI" 
				 WHERE "GDTBI"."removed" IS NULL;
				 
				SELECT "GUBI"."surname", 
					   "GUBI"."firstname",
					   "GUBI"."patronymic"
				  INTO "surname", 
					   "firstname",
					   "patronymic"
				  FROM "GetUserById"("arr"."authorId")
				    AS "GUBI"
				 WHERE "GUBI"."removed" IS NULL;
				RETURN NEXT;
			END;
		END LOOP;
	END;
	$$;


ALTER FUNCTION public."GetDocsWithInfoByUserId"("FltUserId" bigint) OWNER TO postgres;

--
-- TOC entry 357 (class 1255 OID 117998)
-- Name: GetDocumentAndFileByDocumentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDocumentAndFileByDocumentId"("FltDocumentId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "fileId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "documents_and_files"."id",
							"documents_and_files"."documentId",
							"documents_and_files"."fileId",
							"documents_and_files"."created_at",
							"documents_and_files"."updated_at",
							"documents_and_files"."removed"
					FROM "documents_and_files" WHERE "documents_and_files"."documentId" = "FltDocumentId";
	END;
 $$;


ALTER FUNCTION public."GetDocumentAndFileByDocumentId"("FltDocumentId" bigint) OWNER TO postgres;

--
-- TOC entry 358 (class 1255 OID 117999)
-- Name: GetDocumentAndFileById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDocumentAndFileById"("FltId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "fileId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "documents_and_files"."id",
							"documents_and_files"."documentId",
							"documents_and_files"."fileId",
							"documents_and_files"."created_at",
							"documents_and_files"."updated_at",
							"documents_and_files"."removed"
					FROM "documents_and_files" WHERE "documents_and_files"."id" = "FltId";
	END;
 $$;


ALTER FUNCTION public."GetDocumentAndFileById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 360 (class 1255 OID 118000)
-- Name: GetDocumentById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDocumentById"("FltId" bigint) RETURNS TABLE(id bigint, description character varying, "authorId" bigint, file character varying, created_at timestamp without time zone, updated_at timestamp without time zone, "departmentId" bigint, "orderNum" character varying, "deliveryId" smallint, "recorderId" bigint, "baseId" bigint, "baseAssignmentId" bigint, "linkedDocId" bigint, "typeId" smallint, removed timestamp without time zone, name character varying, "creationDate" timestamp without time zone, "closeDate" timestamp without time zone, "coExecutor" character varying, "colName" character varying, "sumContract" character varying, phases text, note character varying, author character varying, "acqDate" timestamp without time zone, customer character varying, addresser character varying, executor bigint, signatory character varying, "letterExecutor" character varying)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "documents"."id",
						"documents"."description",
						"documents"."authorId",
						"documents"."file",
						"documents"."created_at",
						"documents"."updated_at",
						"documents"."departmentId",
						"documents"."orderNum",
						"documents"."deliveryId",
						"documents"."recorderId",
						"documents"."baseId",
						"documents"."baseAssignmentId",
						"documents"."linkedDocId",
						"documents"."typeId",
						"documents"."removed",
						"documents"."name",
						"documents"."creationDate",
						"documents"."closeDate",
						"documents"."coExecutor",
						"documents"."colName",
						"documents"."sumContract",
						"documents"."phases",
						"documents"."note",
						"documents"."author",
						"documents"."acqDate",
						"documents"."customer",
						"documents"."addresser",
						"documents"."executor",
						"documents"."signatory",
						"documents"."letterExecutor"
					FROM "documents" WHERE "documents"."id" = "FltId";
	END;
 $$;


ALTER FUNCTION public."GetDocumentById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 361 (class 1255 OID 118001)
-- Name: GetDocumentTypeById(smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDocumentTypeById"("FltId" smallint) RETURNS TABLE(id smallint, title character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "document_types"."id",
							"document_types"."title",
							"document_types"."created_at",
							"document_types"."updated_at",
							"document_types"."removed"
					FROM "document_types" WHERE "document_types"."id" = "FltId";
	END;
	$$;


ALTER FUNCTION public."GetDocumentTypeById"("FltId" smallint) OWNER TO postgres;

--
-- TOC entry 362 (class 1255 OID 118002)
-- Name: GetDocumentTypes(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDocumentTypes"() RETURNS TABLE(id smallint, title character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "document_types"."id", 
							"document_types"."title",
							"document_types"."created_at",
							"document_types"."updated_at",
							"document_types"."removed"
					FROM "document_types";
	END;
	$$;


ALTER FUNCTION public."GetDocumentTypes"() OWNER TO postgres;

--
-- TOC entry 363 (class 1255 OID 118003)
-- Name: GetDocumentsByString(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetDocumentsByString"("FltString" character varying) RETURNS TABLE(id bigint, description character varying, "authorId" bigint, file character varying, created_at timestamp without time zone, updated_at timestamp without time zone, "departmentId" bigint, "orderNum" character varying, "deliveryId" smallint, "recorderId" bigint, "baseId" bigint, "baseAssignmentId" bigint, "linkedDocId" bigint, "typeId" smallint, removed timestamp without time zone, name character varying, "creationDate" timestamp without time zone, "closeDate" timestamp without time zone, "coExecutor" character varying, "colName" character varying, "sumContract" character varying, phases text, note character varying, author character varying, "acqDate" timestamp without time zone, customer character varying, addresser character varying, executor bigint, signatory character varying, "letterExecutor" character varying)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "documents"."id",
						"documents"."description",
						"documents"."authorId",
						"documents"."file",
						"documents"."created_at",
						"documents"."updated_at",
						"documents"."departmentId",
						"documents"."orderNum",
						"documents"."deliveryId",
						"documents"."recorderId",
						"documents"."baseId",
						"documents"."baseAssignmentId",
						"documents"."linkedDocId",
						"documents"."typeId",
						"documents"."removed",
						"documents"."name",
						"documents"."creationDate",
						"documents"."closeDate",
						"documents"."coExecutor",
						"documents"."colName",
						"documents"."sumContract",
						"documents"."phases",
						"documents"."note",
						"documents"."author",
						"documents"."acqDate",
						"documents"."customer",
						"documents"."addresser",
						"documents"."executor",
						"documents"."signatory",
						"documents"."letterExecutor"
					FROM "documents" WHERE LOWER("documents"."description") SIMILAR TO LOWER(CONCAT('%', "FltString", '%'));
	END;
 $$;


ALTER FUNCTION public."GetDocumentsByString"("FltString" character varying) OWNER TO postgres;

--
-- TOC entry 364 (class 1255 OID 118004)
-- Name: GetFileAdditionByAgreementAndUserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetFileAdditionByAgreementAndUserId"("FltAgreementAndUserId" bigint) RETURNS TABLE(id bigint, "fileId" bigint, "documentId" bigint, "assignmentId" bigint, "feedbackId" bigint, "blogId" bigint, "agreementAndUserId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "files_and_additions"."id",
						"files_and_additions"."fileId",
						"files_and_additions"."documentId",
						"files_and_additions"."assignmentId",
						"files_and_additions"."feedbackId",
						"files_and_additions"."blogId",
						"files_and_additions"."agreementAndUserId",
						"files_and_additions"."created_at",
						"files_and_additions"."updated_at",
						"files_and_additions"."removed"
				   FROM "files_and_additions"
				  WHERE "files_and_additions"."agreementAndUserId" = "FltAgreementAndUserId";
	END;
	$$;


ALTER FUNCTION public."GetFileAdditionByAgreementAndUserId"("FltAgreementAndUserId" bigint) OWNER TO postgres;

--
-- TOC entry 365 (class 1255 OID 118005)
-- Name: GetFileAdditionByAssignmentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetFileAdditionByAssignmentId"("FltAssignmentId" bigint) RETURNS TABLE(id bigint, "fileId" bigint, "documentId" bigint, "assignmentId" bigint, "feedbackId" bigint, "blogId" bigint, "agreementAndUserId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "files_and_additions"."id",
						"files_and_additions"."fileId",
						"files_and_additions"."documentId",
						"files_and_additions"."assignmentId",
						"files_and_additions"."feedbackId",
						"files_and_additions"."blogId",
						"files_and_additions"."agreementAndUserId",
						"files_and_additions"."created_at",
						"files_and_additions"."updated_at",
						"files_and_additions"."removed"
				   FROM "files_and_additions"
				  WHERE "files_and_additions"."assignmentId" = "FltAssignmentId";
	END;
	$$;


ALTER FUNCTION public."GetFileAdditionByAssignmentId"("FltAssignmentId" bigint) OWNER TO postgres;

--
-- TOC entry 366 (class 1255 OID 118006)
-- Name: GetFileAdditionByBlogId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetFileAdditionByBlogId"("FltBlogId" bigint) RETURNS TABLE(id bigint, "fileId" bigint, "documentId" bigint, "assignmentId" bigint, "feedbackId" bigint, "blogId" bigint, "agreementAndUserId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "files_and_additions"."id",
						"files_and_additions"."fileId",
						"files_and_additions"."documentId",
						"files_and_additions"."assignmentId",
						"files_and_additions"."feedbackId",
						"files_and_additions"."blogId",
						"files_and_additions"."agreementAndUserId",
						"files_and_additions"."created_at",
						"files_and_additions"."updated_at",
						"files_and_additions"."removed"
				   FROM "files_and_additions"
				  WHERE "files_and_additions"."blogId" = "FltBlogId";
	END;
	$$;


ALTER FUNCTION public."GetFileAdditionByBlogId"("FltBlogId" bigint) OWNER TO postgres;

--
-- TOC entry 359 (class 1255 OID 118007)
-- Name: GetFileAdditionByDocumentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetFileAdditionByDocumentId"("FltDocumentId" bigint) RETURNS TABLE(id bigint, "fileId" bigint, "documentId" bigint, "assignmentId" bigint, "feedbackId" bigint, "blogId" bigint, "agreementAndUserId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "files_and_additions"."id",
						"files_and_additions"."fileId",
						"files_and_additions"."documentId",
						"files_and_additions"."assignmentId",
						"files_and_additions"."feedbackId",
						"files_and_additions"."blogId",
						"files_and_additions"."agreementAndUserId",
						"files_and_additions"."created_at",
						"files_and_additions"."updated_at",
						"files_and_additions"."removed"
				   FROM "files_and_additions"
				  WHERE "files_and_additions"."documentId" = "FltDocumentId";
	END;
	$$;


ALTER FUNCTION public."GetFileAdditionByDocumentId"("FltDocumentId" bigint) OWNER TO postgres;

--
-- TOC entry 367 (class 1255 OID 118008)
-- Name: GetFileAdditionByFeedbackId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetFileAdditionByFeedbackId"("FltFeedbackId" bigint) RETURNS TABLE(id bigint, "fileId" bigint, "documentId" bigint, "assignmentId" bigint, "feedbackId" bigint, "blogId" bigint, "agreementAndUserId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "files_and_additions"."id",
						"files_and_additions"."fileId",
						"files_and_additions"."documentId",
						"files_and_additions"."assignmentId",
						"files_and_additions"."feedbackId",
						"files_and_additions"."blogId",
						"files_and_additions"."agreementAndUserId",
						"files_and_additions"."created_at",
						"files_and_additions"."updated_at",
						"files_and_additions"."removed"
				   FROM "files_and_additions"
				  WHERE "files_and_additions"."feedbackId" = "FltFeedbackId";
	END;
	$$;


ALTER FUNCTION public."GetFileAdditionByFeedbackId"("FltFeedbackId" bigint) OWNER TO postgres;

--
-- TOC entry 368 (class 1255 OID 118009)
-- Name: GetFileAdditionByFileId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetFileAdditionByFileId"("FltFileId" bigint) RETURNS TABLE(id bigint, "fileId" bigint, "documentId" bigint, "assignmentId" bigint, "feedbackId" bigint, "blogId" bigint, "agreementAndUserId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "files_and_additions"."id",
						"files_and_additions"."fileId",
						"files_and_additions"."documentId",
						"files_and_additions"."assignmentId",
						"files_and_additions"."feedbackId",
						"files_and_additions"."blogId",
						"files_and_additions"."agreementAndUserId",
						"files_and_additions"."created_at",
						"files_and_additions"."updated_at",
						"files_and_additions"."removed"
				   FROM "files_and_additions"
				  WHERE "files_and_additions"."fileId" = "FltFileId";
	END;
	$$;


ALTER FUNCTION public."GetFileAdditionByFileId"("FltFileId" bigint) OWNER TO postgres;

--
-- TOC entry 369 (class 1255 OID 118010)
-- Name: GetFileAdditionById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetFileAdditionById"("FltId" bigint) RETURNS TABLE(id bigint, "fileId" bigint, "documentId" bigint, "assignmentId" bigint, "feedbackId" bigint, "blogId" bigint, "agreementAndUserId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "files_and_additions"."id",
						"files_and_additions"."fileId",
						"files_and_additions"."documentId",
						"files_and_additions"."assignmentId",
						"files_and_additions"."feedbackId",
						"files_and_additions"."blogId",
						"files_and_additions"."agreementAndUserId",
						"files_and_additions"."created_at",
						"files_and_additions"."updated_at",
						"files_and_additions"."removed"
				   FROM "files_and_additions"
				  WHERE "files_and_additions"."id" = "FltId";
	END;
	$$;


ALTER FUNCTION public."GetFileAdditionById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 370 (class 1255 OID 118011)
-- Name: GetFileAndAdditionByAssignmentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetFileAndAdditionByAssignmentId"("FltAssignmentId" bigint) RETURNS TABLE(id bigint, "fileId" bigint, "documentId" bigint, "assignmentId" bigint, "feedbackId" bigint, "blogId" bigint, "agreementAndUserId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "files_and_additions"."id",
						"files_and_additions"."fileId",
						"files_and_additions"."documentId",
						"files_and_additions"."assignmentId",
						"files_and_additions"."feedbackId",
						"files_and_additions"."blogId",
						"files_and_additions"."agreementAndUserId",
						"files_and_additions"."created_at",
						"files_and_additions"."updated_at",
						"files_and_additions"."removed"
				   FROM "files_and_additions"
				  WHERE "files_and_additions"."assignmentId" = "FltAssignmentId";
	END;
	$$;


ALTER FUNCTION public."GetFileAndAdditionByAssignmentId"("FltAssignmentId" bigint) OWNER TO postgres;

--
-- TOC entry 371 (class 1255 OID 118012)
-- Name: GetFileAndAdditionByDocumentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetFileAndAdditionByDocumentId"("FltDocumentId" bigint) RETURNS TABLE(id bigint, "fileId" bigint, "documentId" bigint, "assignmentId" bigint, "feedbackId" bigint, "blogId" bigint, "agreementAndUserId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "files_and_additions"."id",
						"files_and_additions"."fileId",
						"files_and_additions"."documentId",
						"files_and_additions"."assignmentId",
						"files_and_additions"."feedbackId",
						"files_and_additions"."blogId",
						"files_and_additions"."agreementAndUserId",
						"files_and_additions"."created_at",
						"files_and_additions"."updated_at",
						"files_and_additions"."removed"
				   FROM "files_and_additions"
				  WHERE "files_and_additions"."documentId" = "FltDocumentId";
	END;
	$$;


ALTER FUNCTION public."GetFileAndAdditionByDocumentId"("FltDocumentId" bigint) OWNER TO postgres;

--
-- TOC entry 372 (class 1255 OID 118013)
-- Name: GetFileById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetFileById"("FltId" bigint) RETURNS TABLE(id bigint, file character varying, format character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, type smallint, comment character varying)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "files"."id",
							"files"."file",
							"files"."format",
							"files"."created_at",
							"files"."updated_at",
							"files"."removed",
							"files"."type",
							"files"."comment"
					FROM "files" WHERE "files"."id" = "FltId";
	END;
 $$;


ALTER FUNCTION public."GetFileById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 373 (class 1255 OID 118014)
-- Name: GetMailsettingById(smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetMailsettingById"("FltId" smallint) RETURNS TABLE(id smallint, title character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
 BEGIN
 	RETURN QUERY SELECT "mailsettings"."id",
						"mailsettings"."title",
						"mailsettings"."created_at",
						"mailsettings"."updated_at",
						"mailsettings"."removed"
				   FROM "mailsettings"
				  WHERE "mailsettings"."id" = "FltId";
 END
 $$;


ALTER FUNCTION public."GetMailsettingById"("FltId" smallint) OWNER TO postgres;

--
-- TOC entry 374 (class 1255 OID 118015)
-- Name: GetMailsettingsUsersById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetMailsettingsUsersById"("FltId" bigint) RETURNS TABLE(id bigint, "userId" bigint, "settingId" smallint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, status boolean)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "mailsettings_users"."id",
						"mailsettings_users"."userId",
						"mailsettings_users"."settingId",
						"mailsettings_users"."created_at",
						"mailsettings_users"."updated_at",
						"mailsettings_users"."removed",
						"mailsettings_users"."status"
				FROM "mailsettings_users" 
				WHERE "mailsettings_users"."id" = "FltId";
	END;
 $$;


ALTER FUNCTION public."GetMailsettingsUsersById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 375 (class 1255 OID 118016)
-- Name: GetMailsettingsUsersByUserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetMailsettingsUsersByUserId"("FltUserId" bigint) RETURNS TABLE(id bigint, "userId" bigint, "settingId" smallint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, status boolean)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "mailsettings_users"."id",
						"mailsettings_users"."userId",
						"mailsettings_users"."settingId",
						"mailsettings_users"."created_at",
						"mailsettings_users"."updated_at",
						"mailsettings_users"."removed",
						"mailsettings_users"."status"
				FROM "mailsettings_users" 
				WHERE "mailsettings_users"."userId" = "FltUserId";
	END;
 $$;


ALTER FUNCTION public."GetMailsettingsUsersByUserId"("FltUserId" bigint) OWNER TO postgres;

--
-- TOC entry 353 (class 1255 OID 118017)
-- Name: GetMailsettingsUsersByUserIdAndSettingId(bigint, smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetMailsettingsUsersByUserIdAndSettingId"("FltUserId" bigint, "FltSettingId" smallint) RETURNS TABLE(id bigint, "userId" bigint, "settingId" smallint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, status boolean)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "mailsettings_users"."id",
						"mailsettings_users"."userId",
						"mailsettings_users"."settingId",
						"mailsettings_users"."created_at",
						"mailsettings_users"."updated_at",
						"mailsettings_users"."removed",
						"mailsettings_users"."status"
				FROM "mailsettings_users" 
				WHERE "mailsettings_users"."userId" = "FltUserId"
				  AND "mailsettings_users"."settingId" = "FltSettingId";
	END;
 $$;


ALTER FUNCTION public."GetMailsettingsUsersByUserIdAndSettingId"("FltUserId" bigint, "FltSettingId" smallint) OWNER TO postgres;

--
-- TOC entry 376 (class 1255 OID 118018)
-- Name: GetNonViewedAgreementAndUserByUserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetNonViewedAgreementAndUserByUserId"("FltUserId" bigint) RETURNS TABLE(id bigint, "agreementId" bigint, "userId" bigint, note text, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, approved_at timestamp without time zone, viewed_at timestamp without time zone, "order" smallint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "agreements_and_users"."id",
							"agreements_and_users"."agreementId",
							"agreements_and_users"."userId",
							"agreements_and_users"."note",
							"agreements_and_users"."created_at",
							"agreements_and_users"."updated_at",
							"agreements_and_users"."removed",
							"agreements_and_users"."refused_at",
							"agreements_and_users"."approved_at",
							"agreements_and_users"."viewed_at",
							"agreements_and_users"."order"
					FROM "agreements_and_users" WHERE "agreements_and_users"."userId" = "FltUserId" AND "agreements_and_users"."viewed_at" IS NULL;
	END;
	$$;


ALTER FUNCTION public."GetNonViewedAgreementAndUserByUserId"("FltUserId" bigint) OWNER TO postgres;

--
-- TOC entry 377 (class 1255 OID 118019)
-- Name: GetRoleById(smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetRoleById"("FltRoleId" smallint) RETURNS TABLE(id smallint, title character varying, slug character varying, created_at timestamp without time zone, updated_at timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "roles"."id",
							"roles"."title",
							"roles"."slug",
							"roles"."created_at",
							"roles"."updated_at"
					FROM "roles" 
					WHERE "roles"."id" = "FltRoleId" AND "roles"."removed" IS NULL;
	END;
	$$;


ALTER FUNCTION public."GetRoleById"("FltRoleId" smallint) OWNER TO postgres;

--
-- TOC entry 378 (class 1255 OID 118020)
-- Name: GetRoleSlugByUserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetRoleSlugByUserId"("FltUserId" bigint) RETURNS TABLE(slug character varying)
    LANGUAGE plpgsql
    AS $$
	DECLARE 
		"RoleId" smallint;
	BEGIN 	
		SELECT "roleid" INTO "RoleId"
			FROM "GetUserById"("FltUserId");
		RETURN QUERY SELECT "roles"."slug" FROM "roles" WHERE "roles"."id" = "RoleId";
	END;
	$$;


ALTER FUNCTION public."GetRoleSlugByUserId"("FltUserId" bigint) OWNER TO postgres;

--
-- TOC entry 379 (class 1255 OID 118021)
-- Name: GetStatusByAlias(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetStatusByAlias"("FltAlias" character varying) RETURNS TABLE(id smallint, title character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, alias character varying)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "statuses"."id",
					"statuses"."title",
					"statuses"."created_at",
					"statuses"."updated_at",
					"statuses"."removed",
					"statuses"."alias"
			FROM "statuses" WHERE "statuses"."alias" = "FltAlias";
	END;
$$;


ALTER FUNCTION public."GetStatusByAlias"("FltAlias" character varying) OWNER TO postgres;

--
-- TOC entry 380 (class 1255 OID 118022)
-- Name: GetStatusById(smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetStatusById"("FltId" smallint) RETURNS TABLE(id smallint, title character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, alias character varying)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "statuses"."id",
					"statuses"."title",
					"statuses"."created_at",
					"statuses"."updated_at",
					"statuses"."removed",
					"statuses"."alias"
			FROM "statuses" WHERE "statuses"."id" = "FltId";
	END;
$$;


ALTER FUNCTION public."GetStatusById"("FltId" smallint) OWNER TO postgres;

--
-- TOC entry 381 (class 1255 OID 118023)
-- Name: GetStatusesByGroup(smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetStatusesByGroup"("FltGroup" smallint) RETURNS TABLE(id smallint, title character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, alias character varying, "group" smallint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		RETURN QUERY SELECT "statuses"."id",
					"statuses"."title",
					"statuses"."created_at",
					"statuses"."updated_at",
					"statuses"."removed",
					"statuses"."alias",
					"statuses"."group"
			FROM "statuses" WHERE "statuses"."group" = "FltGroup";
	END;
$$;


ALTER FUNCTION public."GetStatusesByGroup"("FltGroup" smallint) OWNER TO postgres;

--
-- TOC entry 382 (class 1255 OID 118024)
-- Name: GetUserAndDepartmentByDepartmentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetUserAndDepartmentByDepartmentId"("FltDepartmentId" bigint) RETURNS TABLE(id bigint, "userId" bigint, "departmentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "users_and_departments"."id", 
							"users_and_departments"."userId",
							"users_and_departments"."departmentId",
							"users_and_departments"."created_at",
							"users_and_departments"."updated_at",
							"users_and_departments"."removed"
					FROM "users_and_departments" 
					WHERE "users_and_departments"."departmentId" = "FltDepartmentId";
	END;
	$$;


ALTER FUNCTION public."GetUserAndDepartmentByDepartmentId"("FltDepartmentId" bigint) OWNER TO postgres;

--
-- TOC entry 383 (class 1255 OID 118025)
-- Name: GetUserAndDepartmentByUserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetUserAndDepartmentByUserId"("FltUserId" bigint) RETURNS TABLE(id bigint, "userId" bigint, "departmentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "users_and_departments"."id", 
							"users_and_departments"."userId",
							"users_and_departments"."departmentId",
							"users_and_departments"."created_at",
							"users_and_departments"."updated_at",
							"users_and_departments"."removed"
					FROM "users_and_departments" 
					WHERE "users_and_departments"."userId" = "FltUserId";
	END;
	$$;


ALTER FUNCTION public."GetUserAndDepartmentByUserId"("FltUserId" bigint) OWNER TO postgres;

--
-- TOC entry 384 (class 1255 OID 118026)
-- Name: GetUserById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetUserById"("FltId" bigint) RETURNS TABLE(id bigint, login character varying, surname character varying, firstname character varying, patronymic character varying, department integer, email character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, roleid smallint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "users"."id", 
							"users"."login",
							"users"."surname",
							"users"."firstname",
							"users"."patronymic",
							"users"."department",
							"users"."email",
							"users"."created_at",
							"users"."updated_at",
							"users"."removed",
							"users"."roleid"
					FROM "users" 
					WHERE "users"."id" = "FltId";
	END;
	$$;


ALTER FUNCTION public."GetUserById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 385 (class 1255 OID 118027)
-- Name: GetUserByLogin(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."GetUserByLogin"("FltLogin" character varying) RETURNS TABLE(id bigint, login character varying, surname character varying, firstname character varying, patronymic character varying, department integer, email character varying, created_at timestamp without time zone, updated_at timestamp without time zone, roleid smallint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
		RETURN QUERY SELECT "users"."id", 
							"users"."login",
							"users"."surname",
							"users"."firstname",
							"users"."patronymic",
							"users"."department",
							"users"."email",
							"users"."created_at",
							"users"."updated_at",
							"users"."roleid"
					FROM "users" 
					WHERE "users"."login" = "FltLogin";
	END;
	$$;


ALTER FUNCTION public."GetUserByLogin"("FltLogin" character varying) OWNER TO postgres;

--
-- TOC entry 386 (class 1255 OID 118028)
-- Name: RefuseAgreement(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RefuseAgreement"("FltAgreementId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, agreed_at timestamp without time zone, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, deadline timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "agreements" 
		SET "refused_at" = current_timestamp,
			"updated_at" = current_timestamp
		WHERE "agreements"."id" = "FltAgreementId";
	RETURN QUERY SELECT "agreements"."id",
					"agreements"."documentId",
					"agreements"."agreed_at",
					"agreements"."created_at",
					"agreements"."updated_at",
					"agreements"."removed",
					"agreements"."refused_at",
					"agreements"."deadline"
			FROM "agreements" 
			WHERE "agreements"."id" = "FltAgreementId";
END
$$;


ALTER FUNCTION public."RefuseAgreement"("FltAgreementId" bigint) OWNER TO postgres;

--
-- TOC entry 387 (class 1255 OID 118029)
-- Name: RefuseAgreementAndUser(bigint, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RefuseAgreementAndUser"("FltId" bigint, "FltNote" text) RETURNS TABLE(id bigint, "agreementId" bigint, "userId" bigint, note text, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, approved_at timestamp without time zone, viewed_at timestamp without time zone, "order" smallint)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "agreements_and_users" 
		SET "refused_at" = current_timestamp,
			"updated_at" = current_timestamp,
			"note" = "FltNote"
		WHERE "agreements_and_users"."id" = "FltId";
	RETURN QUERY SELECT "agreements_and_users"."id",
						"agreements_and_users"."agreementId",
						"agreements_and_users"."userId",
						"agreements_and_users"."note",
						"agreements_and_users"."created_at",
						"agreements_and_users"."updated_at",
						"agreements_and_users"."removed",
						"agreements_and_users"."refused_at",
						"agreements_and_users"."approved_at",
						"agreements_and_users"."viewed_at",
						"agreements_and_users"."order"
				FROM "agreements_and_users" 
				WHERE "agreements_and_users"."id" = "FltId";
END
$$;


ALTER FUNCTION public."RefuseAgreementAndUser"("FltId" bigint, "FltNote" text) OWNER TO postgres;

--
-- TOC entry 388 (class 1255 OID 118030)
-- Name: RefuseAgreementsAndUsersById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RefuseAgreementsAndUsersById"("FltId" bigint) RETURNS TABLE(id bigint, "agreementId" bigint, "userId" bigint, note text, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused timestamp without time zone, approved_at timestamp without time zone, viewed_at timestamp without time zone, "order" smallint)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "agreements_and_users" 
		SET "updated_at" = current_timestamp, "refused_at" = current_timestamp, "approved_at" = NULL
		WHERE "agreements_and_users"."id" = "FltId";
		RETURN QUERY SELECT "agreements_and_users"."id",
						"agreements_and_users"."agreementId",
						"agreements_and_users"."userId",
						"agreements_and_users"."note",
						"agreements_and_users"."created_at",
						"agreements_and_users"."updated_at",
						"agreements_and_users"."removed",
						"agreements_and_users"."refused_at",
						"agreements_and_users"."approved_at",
						"agreements_and_users"."viewed_at",
						"agreements_and_users"."order"
				FROM "agreements_and_users" 
				WHERE "agreements_and_users"."id" = "FltId";
END
$$;


ALTER FUNCTION public."RefuseAgreementsAndUsersById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 389 (class 1255 OID 118031)
-- Name: RefuseAssignmentDeadline(bigint, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RefuseAssignmentDeadline"("FltId" bigint, "FltApproverId" bigint) RETURNS TABLE(id bigint, "assignmentId" bigint, "initiatorId" bigint, "approvedUserId" bigint, created_at timestamp without time zone, deadline timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, initiated_at timestamp without time zone, approved_at timestamp without time zone, refused_at timestamp without time zone, comment character varying, "fileId" bigint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		UPDATE "assignments_deadlines" 
			SET "refused_at" = current_timestamp,
				"updated_at" = current_timestamp,
				"approvedUserId" = "FltApproverId"
		WHERE "assignments_deadlines"."id" = "FltId";
		RETURN QUERY SELECT "assignments_deadlines"."id",
							"assignments_deadlines"."assignmentId",
							"assignments_deadlines"."initiatorId",
							"assignments_deadlines"."approvedUserId",
							"assignments_deadlines"."created_at",
							"assignments_deadlines"."deadline",
							"assignments_deadlines"."updated_at",
							"assignments_deadlines"."removed",
							"assignments_deadlines"."initiated_at",
							"assignments_deadlines"."approved_at",
							"assignments_deadlines"."refused_at",
							"assignments_deadlines"."comment",
						"assignments_deadlines"."fileId"
					FROM "assignments_deadlines" WHERE "assignments_deadlines"."id" = "FltId";
	END;
 $$;


ALTER FUNCTION public."RefuseAssignmentDeadline"("FltId" bigint, "FltApproverId" bigint) OWNER TO postgres;

--
-- TOC entry 390 (class 1255 OID 118032)
-- Name: RemoveAcquaintance(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RemoveAcquaintance"("FltId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "userId" bigint, "initiatorId" bigint, seen_at timestamp without time zone, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "acquaintances" 
		SET "removed" = current_timestamp
		WHERE "acquaintances"."id" = "FltId";
	RETURN QUERY SELECT "acquaintances"."id", 
						"acquaintances"."documentId",
						"acquaintances"."userId",
						"acquaintances"."initiatorId",
						"acquaintances"."seen_at",
						"acquaintances"."created_at",
						"acquaintances"."updated_at",					
						"acquaintances"."removed"
		FROM "acquaintances" 
		WHERE "acquaintances"."id" = "FltId";
END
$$;


ALTER FUNCTION public."RemoveAcquaintance"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 391 (class 1255 OID 118033)
-- Name: RemoveAgreement(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RemoveAgreement"("FltId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, agreed_at timestamp without time zone, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, deadline timestamp without time zone)
    LANGUAGE plpgsql
    AS $$ 
BEGIN
	UPDATE "agreements" 
		SET "removed" = current_timestamp
		WHERE "agreements"."id" = "FltId";
	RETURN QUERY SELECT "agreements"."id",
						"agreements"."documentId",
						"agreements"."agreed_at",
						"agreements"."created_at",
						"agreements"."updated_at",
						"agreements"."removed",
						"agreements"."deadline"
				FROM "agreements" WHERE "agreements"."id" = "FltId";
END
$$;


ALTER FUNCTION public."RemoveAgreement"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 392 (class 1255 OID 118034)
-- Name: RemoveAgreementAndUser(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RemoveAgreementAndUser"("FltId" bigint) RETURNS TABLE(id bigint, "agreementId" bigint, "userId" bigint, note text, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, approved_at timestamp without time zone, viewed_at timestamp without time zone, "order" smallint)
    LANGUAGE plpgsql
    AS $$ 
BEGIN
	UPDATE "agreements_and_users" 
		SET "removed" = current_timestamp
		WHERE "agreements_and_users"."id" = "FltId";
	RETURN QUERY SELECT "agreements_and_users"."id",
						"agreements_and_users"."agreementId",
						"agreements_and_users"."userId",
						"agreements_and_users"."note",
						"agreements_and_users"."created_at",
						"agreements_and_users"."updated_at",
						"agreements_and_users"."removed",
						"agreements_and_users"."refused_at",
						"agreements_and_users"."approved_at",
						"agreements_and_users"."viewed_at",
						"agreements_and_users"."order"
				FROM "agreements_and_users" WHERE "agreements_and_users"."id" = "FltId";
END
$$;


ALTER FUNCTION public."RemoveAgreementAndUser"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 393 (class 1255 OID 118035)
-- Name: RemoveBlogItem(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RemoveBlogItem"("FltId" bigint) RETURNS TABLE(id bigint, title character varying, text character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "blog" 
 				SET "removed" = current_timestamp
 				WHERE "blog"."id" = "FltId";
	RETURN QUERY SELECT "blog"."id",
						"blog"."title",
						"blog"."text",
						"blog"."created_at",
						"blog"."updated_at",
						"blog"."removed"
				   FROM "blog" 
				  WHERE "blog"."id" = "FltId";
END
$$;


ALTER FUNCTION public."RemoveBlogItem"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 394 (class 1255 OID 118036)
-- Name: RemoveControlByAssignmentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RemoveControlByAssignmentId"("FltAssignmentId" bigint) RETURNS TABLE(id bigint, "userId" bigint, "assignmentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, viewed_at timestamp without time zone, "initiatorId" bigint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		UPDATE "controls" 
			SET "removed" = current_timestamp
			WHERE "controls"."assignmentId" = "FltAssignmentId";
		RETURN QUERY SELECT "controls"."id",
							"controls"."userId",
							"controls"."assignmentId",
							"controls"."created_at",
							"controls"."updated_at",
							"controls"."removed",
							"controls"."viewed_at",
							"controls"."initiatorId"
					   FROM "controls"
					  WHERE "controls"."assignmentId" = "FltAssignmentId";
	END;
 $$;


ALTER FUNCTION public."RemoveControlByAssignmentId"("FltAssignmentId" bigint) OWNER TO postgres;

--
-- TOC entry 395 (class 1255 OID 118037)
-- Name: RemoveControlById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RemoveControlById"("FltId" bigint) RETURNS TABLE(id bigint, "userId" bigint, "assignmentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, viewed_at timestamp without time zone, "initiatorId" bigint)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "controls" 
		SET "removed" = current_timestamp
		WHERE "controls"."id" = "FltId";
 	RETURN QUERY SELECT "controls"."id",
						"controls"."userId",
						"controls"."assignmentId",
						"controls"."created_at",
						"controls"."updated_at",
						"controls"."removed",
						"controls"."viewed_at",
						"controls"."initiatorId"
				   FROM "controls"
				  WHERE "controls"."id" = "FltId";
END
$$;


ALTER FUNCTION public."RemoveControlById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 396 (class 1255 OID 118038)
-- Name: RemoveControlByUserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RemoveControlByUserId"("FltUserId" bigint) RETURNS TABLE(id bigint, "userId" bigint, "assignmentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, viewed_at timestamp without time zone, "initiatorId" bigint)
    LANGUAGE plpgsql
    AS $$
	BEGIN 		
		UPDATE "controls" 
			SET "removed" = current_timestamp
			WHERE "controls"."assignmentId" = "FltUserId";
		RETURN QUERY SELECT "controls"."id",
							"controls"."userId",
							"controls"."assignmentId",
							"controls"."created_at",
							"controls"."updated_at",
							"controls"."removed",
							"controls"."viewed_at",
							"controls"."initiatorId"
					   FROM "controls"
					  WHERE "controls"."userId" = "FltUserId";
	END;
 $$;


ALTER FUNCTION public."RemoveControlByUserId"("FltUserId" bigint) OWNER TO postgres;

--
-- TOC entry 397 (class 1255 OID 118039)
-- Name: RemoveDocument(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RemoveDocument"("FltId" bigint) RETURNS TABLE(id bigint, description character varying, "authorId" bigint, file character varying, created_at timestamp without time zone, updated_at timestamp without time zone, "departmentId" bigint, "orderNum" character varying, "deliveryId" smallint, "recorderId" bigint, "baseId" bigint, "baseAssignmentId" bigint, "linkedDocId" bigint, "typeId" smallint, removed timestamp without time zone, name character varying, "creationDate" timestamp without time zone, "closeDate" timestamp without time zone, "coExecutor" character varying, "colName" character varying, "sumContract" character varying, phases text, note character varying, author character varying, "acqDate" timestamp without time zone, customer character varying, addresser character varying, executor bigint, signatory character varying, "letterExecutor" character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "documents" 
		SET "removed" = current_timestamp
		WHERE "documents"."id" = "FltId";
	RETURN QUERY SELECT "documents"."id",
						"documents"."description",
						"documents"."authorId",
						"documents"."file",
						"documents"."created_at",
						"documents"."updated_at",
						"documents"."departmentId",
						"documents"."orderNum",
						"documents"."deliveryId",
						"documents"."recorderId",
						"documents"."baseId",
						"documents"."baseAssignmentId",
						"documents"."linkedDocId",
						"documents"."typeId",
						"documents"."removed",
						"documents"."name",
						"documents"."creationDate",
						"documents"."closeDate",
						"documents"."coExecutor",
						"documents"."colName",
						"documents"."sumContract",
						"documents"."phases",
						"documents"."note",
						"documents"."author",
						"documents"."acqDate",
						"documents"."customer",
						"documents"."addresser",
						"documents"."executor",
						"documents"."signatory",
						"documents"."letterExecutor"
				FROM "documents" WHERE "documents"."id" = "FltId";
END
$$;


ALTER FUNCTION public."RemoveDocument"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 398 (class 1255 OID 118040)
-- Name: RemoveFileAdditionByAssignmentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RemoveFileAdditionByAssignmentId"("FltAssignmentId" bigint) RETURNS TABLE(id bigint, "fileId" bigint, "documentId" bigint, "assignmentId" bigint, "feedbackId" bigint, "blogId" bigint, "agreementAndUserId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "files_and_additions" 
		SET "updated_at" = current_timestamp,
			"removed" = current_timestamp
		WHERE "files_and_additions"."assignmentId" = "FltAssignmentId" 
		  AND "files_and_additions"."removed" IS NULL;
	RETURN QUERY SELECT "files_and_additions"."id",
						"files_and_additions"."fileId",
						"files_and_additions"."documentId",
						"files_and_additions"."assignmentId",
						"files_and_additions"."feedbackId",
						"files_and_additions"."blogId",
						"files_and_additions"."agreementAndUserId",
						"files_and_additions"."created_at",
						"files_and_additions"."updated_at",
						"files_and_additions"."removed"
				   FROM "files_and_additions"
				  WHERE "files_and_additions"."assignmentId" = "FltAssignmentId";
END
$$;


ALTER FUNCTION public."RemoveFileAdditionByAssignmentId"("FltAssignmentId" bigint) OWNER TO postgres;

--
-- TOC entry 399 (class 1255 OID 118041)
-- Name: RemoveFileAdditionByDocumentId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RemoveFileAdditionByDocumentId"("FltDocumentId" bigint) RETURNS TABLE(id bigint, "fileId" bigint, "documentId" bigint, "assignmentId" bigint, "feedbackId" bigint, "blogId" bigint, "agreementAndUserId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "files_and_additions" 
		SET "updated_at" = current_timestamp,
			"removed" = current_timestamp
		WHERE "files_and_additions"."documentId" = "FltDocumentId" 
		  AND "files_and_additions"."removed" IS NULL;
	RETURN QUERY SELECT "files_and_additions"."id",
						"files_and_additions"."fileId",
						"files_and_additions"."documentId",
						"files_and_additions"."assignmentId",
						"files_and_additions"."feedbackId",
						"files_and_additions"."blogId",
						"files_and_additions"."agreementAndUserId",
						"files_and_additions"."created_at",
						"files_and_additions"."updated_at",
						"files_and_additions"."removed"
				   FROM "files_and_additions"
				  WHERE "files_and_additions"."documentId" = "FltDocumentId";
END
$$;


ALTER FUNCTION public."RemoveFileAdditionByDocumentId"("FltDocumentId" bigint) OWNER TO postgres;

--
-- TOC entry 400 (class 1255 OID 118042)
-- Name: RemoveFileAdditionById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RemoveFileAdditionById"("FltId" bigint) RETURNS TABLE(id bigint, "fileId" bigint, "documentId" bigint, "assignmentId" bigint, "feedbackId" bigint, "blogId" bigint, "agreementAndUserId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "files_and_additions" 
		SET "updated_at" = current_timestamp,
			"removed" = current_timestamp
		WHERE "files_and_additions"."id" = "FltId";
	RETURN QUERY SELECT "files_and_additions"."id",
						"files_and_additions"."fileId",
						"files_and_additions"."documentId",
						"files_and_additions"."assignmentId",
						"files_and_additions"."feedbackId",
						"files_and_additions"."blogId",
						"files_and_additions"."agreementAndUserId",
						"files_and_additions"."created_at",
						"files_and_additions"."updated_at",
						"files_and_additions"."removed"
				   FROM "files_and_additions"
				  WHERE "files_and_additions"."id" = "FltId";
END
$$;


ALTER FUNCTION public."RemoveFileAdditionById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 401 (class 1255 OID 118043)
-- Name: RemoveUserAndDepartmentByUserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RemoveUserAndDepartmentByUserId"("FltUserId" bigint) RETURNS TABLE(id bigint, "userId" bigint, "departmentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "users_and_departments" 
		SET "removed" = current_timestamp
		WHERE "users_and_departments"."userId" = "FltUserId";
	RETURN QUERY SELECT "users_and_departments"."id",
						"users_and_departments"."userId",
						"users_and_departments"."departmentId",
						"users_and_departments"."created_at",
						"users_and_departments"."updated_at",
						"users_and_departments"."removed"
				FROM "users_and_departments" WHERE "users_and_departments"."userId" = "FltUserId";
END
$$;


ALTER FUNCTION public."RemoveUserAndDepartmentByUserId"("FltUserId" bigint) OWNER TO postgres;

--
-- TOC entry 402 (class 1255 OID 118044)
-- Name: RemoveUserByUserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."RemoveUserByUserId"("FltId" bigint) RETURNS TABLE(id bigint, login character varying, surname character varying, firstname character varying, patronymic character varying, department integer, email character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, roleid smallint)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "users" 
		SET "updated_at" = current_timestamp,
			"removed" = current_timestamp
		WHERE "users"."id" = "FltId";
	RETURN QUERY SELECT  "users"."id",
						 "users"."login",
						 "users"."surname",
						 "users"."firstname",
						 "users"."patronymic",
						 "users"."department",
						 "users"."email",
						 "users"."created_at",
						 "users"."updated_at",
						 "users"."removed",
						 "users"."roleid"
				FROM "users" WHERE "users"."id" = "FltId";
END
$$;


ALTER FUNCTION public."RemoveUserByUserId"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 403 (class 1255 OID 118045)
-- Name: UnblockUserByUserId(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."UnblockUserByUserId"("FltId" bigint) RETURNS TABLE(id bigint, login character varying, surname character varying, firstname character varying, patronymic character varying, department integer, email character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, roleid smallint)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "users" 
		SET "removed" = NULL, 
			"updated_at" = current_timestamp
		WHERE "users"."id" = "FltId";
	RETURN QUERY SELECT  "users"."id",
						 "users"."login",
						 "users"."surname",
						 "users"."firstname",
						 "users"."patronymic",
						 "users"."department",
						 "users"."email",
						 "users"."created_at",
						 "users"."updated_at",
						 "users"."removed",
						 "users"."roleid"
				FROM "users" WHERE "users"."id" = "FltId";
END
$$;


ALTER FUNCTION public."UnblockUserByUserId"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 404 (class 1255 OID 118046)
-- Name: UpdateAcquaintanceWithSeen(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."UpdateAcquaintanceWithSeen"("FltId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "userId" bigint, "initiatorId" bigint, seen_at timestamp without time zone, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "acquaintances" 
		SET "seen_at" = current_timestamp,
			"updated_at" = current_timestamp
		WHERE "acquaintances"."id" = "FltId";
	RETURN QUERY SELECT "acquaintances"."id", 
						"acquaintances"."documentId",
						"acquaintances"."userId",
						"acquaintances"."initiatorId",
						"acquaintances"."seen_at",
						"acquaintances"."created_at",
						"acquaintances"."updated_at",					
						"acquaintances"."removed"
		FROM "acquaintances" 
		WHERE "acquaintances"."id" = "FltId";
END
$$;


ALTER FUNCTION public."UpdateAcquaintanceWithSeen"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 405 (class 1255 OID 118047)
-- Name: UpdateAgreementUserWithCreatedAt(bigint, smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."UpdateAgreementUserWithCreatedAt"("FltAgreementId" bigint, "FltOrder" smallint) RETURNS TABLE(id bigint, "agreementId" bigint, "userId" bigint, note text, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused timestamp without time zone, approved_at timestamp without time zone, viewed_at timestamp without time zone, "order" smallint)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "agreements_and_users" 
		SET "created_at" = current_timestamp
		WHERE "agreements_and_users"."agreementId" = "FltAgreementId"
		  AND "agreements_and_users"."order" = "FltOrder";
	RETURN QUERY SELECT "agreements_and_users"."id",
						"agreements_and_users"."agreementId",
						"agreements_and_users"."userId",
						"agreements_and_users"."note",
						"agreements_and_users"."created_at",
						"agreements_and_users"."updated_at",
						"agreements_and_users"."removed",
						"agreements_and_users"."refused_at",
						"agreements_and_users"."approved_at",
						"agreements_and_users"."viewed_at",
						"agreements_and_users"."order"
				FROM "agreements_and_users" 
				WHERE "agreements_and_users"."agreementId" = "FltAgreementId"
				  AND "agreements_and_users"."order" = "FltOrder";
END
$$;


ALTER FUNCTION public."UpdateAgreementUserWithCreatedAt"("FltAgreementId" bigint, "FltOrder" smallint) OWNER TO postgres;

--
-- TOC entry 406 (class 1255 OID 118048)
-- Name: UpdateAgreementUserWithNote(bigint, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."UpdateAgreementUserWithNote"("FltId" bigint, "FltNote" text) RETURNS TABLE(id bigint, "agreementId" bigint, "userId" bigint, note text, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused timestamp without time zone, approved_at timestamp without time zone, viewed_at timestamp without time zone, "order" smallint)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "agreements_and_users" 
		SET "note" = "FltNote",
			"updated_at" = current_timestamp
		WHERE "agreements_and_users"."id" = "FltId";
	RETURN QUERY SELECT "agreements_and_users"."id",
						"agreements_and_users"."agreementId",
						"agreements_and_users"."userId",
						"agreements_and_users"."note",
						"agreements_and_users"."created_at",
						"agreements_and_users"."updated_at",
						"agreements_and_users"."removed",
						"agreements_and_users"."refused_at",
						"agreements_and_users"."approved_at",
						"agreements_and_users"."viewed_at",
						"agreements_and_users"."order"
				FROM "agreements_and_users" 
				WHERE "agreements_and_users"."id" = "FltId";
END
$$;


ALTER FUNCTION public."UpdateAgreementUserWithNote"("FltId" bigint, "FltNote" text) OWNER TO postgres;

--
-- TOC entry 407 (class 1255 OID 118049)
-- Name: UpdateAssignmentInfo(bigint, smallint, character varying, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."UpdateAssignmentInfo"("FltAssignmentId" bigint, "FltTypeId" smallint, "FltText" character varying, "FltDescription" text) RETURNS TABLE(id bigint, "documentId" bigint, "typeId" smallint, "authorId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, text character varying, "executorId" bigint, "baseId" bigint, viewed_at timestamp without time zone, "mainId" bigint, description text)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
	UPDATE "assignments" 
		SET "updated_at" = current_timestamp,
			"typeId" = "FltTypeId",
			"text" = "FltText",
			"description" = "FltDescription"
		WHERE "assignments"."id" = "FltAssignmentId";
		RETURN QUERY SELECT "assignments"."id",
					"assignments"."documentId",
					"assignments"."typeId",
					"assignments"."authorId",
					"assignments"."created_at",
					"assignments"."updated_at",
					"assignments"."removed",
					"assignments"."text",
					"assignments"."executorId",
					"assignments"."baseId",
					"assignments"."viewed_at",
					"assignments"."mainId",
					"assignments"."description"
			FROM "assignments" WHERE "assignments"."id" = "FltAssignmentId";
	END;
	$$;


ALTER FUNCTION public."UpdateAssignmentInfo"("FltAssignmentId" bigint, "FltTypeId" smallint, "FltText" character varying, "FltDescription" text) OWNER TO postgres;

--
-- TOC entry 408 (class 1255 OID 118050)
-- Name: UpdateAssignmentWithMainId(bigint, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."UpdateAssignmentWithMainId"("FltId" bigint, "FltMainId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "typeId" smallint, "authorId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, text character varying, "executorId" bigint, "baseId" bigint, viewed_at timestamp without time zone, "mainId" bigint, description text)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "assignments" 
		SET "mainId" = "FltMainId"
		WHERE "assignments"."id" = "FltId";
	RETURN QUERY SELECT "assignments"."id",
						"assignments"."documentId",
						"assignments"."typeId",
						"assignments"."authorId",
						"assignments"."created_at",
						"assignments"."updated_at",
						"assignments"."removed",
						"assignments"."text",
						"assignments"."executorId",
						"assignments"."baseId",
						"assignments"."viewed_at",
						"assignments"."mainId",
						"assignments"."description"
				FROM "assignments" 
				WHERE "assignments"."id" = "FltId";
END
$$;


ALTER FUNCTION public."UpdateAssignmentWithMainId"("FltId" bigint, "FltMainId" bigint) OWNER TO postgres;

--
-- TOC entry 409 (class 1255 OID 118051)
-- Name: UpdateBlogItem(bigint, character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."UpdateBlogItem"("FltId" bigint, "FltTitle" character varying, "FltText" character varying) RETURNS TABLE(id bigint, title character varying, text character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "blog" 
 				SET "title" = "FltTitle",
 					"text" = "FltText",
 					"updated_at" = current_timestamp
 				WHERE "blog"."id" = "FltId";
	RETURN QUERY SELECT "blog"."id",
						"blog"."title",
						"blog"."text",
						"blog"."created_at",
						"blog"."updated_at",
						"blog"."removed"
				   FROM "blog" 
				  WHERE "blog"."id" = "FltId";
END
$$;


ALTER FUNCTION public."UpdateBlogItem"("FltId" bigint, "FltTitle" character varying, "FltText" character varying) OWNER TO postgres;

--
-- TOC entry 410 (class 1255 OID 118052)
-- Name: UpdateDiruser(bigint, character varying, character varying, character varying, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."UpdateDiruser"("FltId" bigint, "FltSurname" character varying, "FltFirstname" character varying, "FltPatronymic" character varying, "FltDepartmentId" integer) RETURNS TABLE(id bigint, surname character varying, firstname character varying, patronymic character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, "departmentId" integer)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "dirusers" 
		SET "surname" = "FltSurname",
			"firstname" = "FltFirstname",
			"patronymic" = "FltPatronymic",
			"departmentId" = "FltDepartmentId",
			"updated_at" = current_timestamp
		WHERE "dirusers"."id" = "FltId";
	RETURN QUERY SELECT "dirusers"."id",
						"dirusers"."surname",
						"dirusers"."firstname",
						"dirusers"."patronymic",
						"dirusers"."created_at",
						"dirusers"."updated_at",
						"dirusers"."removed",
						"dirusers"."departmentId"
				FROM "dirusers" WHERE "dirusers"."id" = "FltId";
END
$$;


ALTER FUNCTION public."UpdateDiruser"("FltId" bigint, "FltSurname" character varying, "FltFirstname" character varying, "FltPatronymic" character varying, "FltDepartmentId" integer) OWNER TO postgres;

--
-- TOC entry 411 (class 1255 OID 118053)
-- Name: UpdateDocumentInfo(bigint, character varying, character varying, character varying, timestamp without time zone, timestamp without time zone, character varying, character varying, character varying, text, character varying, character varying, timestamp without time zone, character varying, character varying, character varying, bigint, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."UpdateDocumentInfo"("FltDocumentId" bigint, "FltDescription" character varying, "FltOrderNum" character varying, "FltName" character varying, "FltCreationDate" timestamp without time zone, "FltCloseDate" timestamp without time zone, "FltCoExecutor" character varying, "FltColName" character varying, "FltSumContract" character varying, "FltPhases" text, "FltNote" character varying, "FltAuthor" character varying, "FltAcqDate" timestamp without time zone, "FltCustomer" character varying, "FltAddresser" character varying, "FltSignatory" character varying, "FltExecutor" bigint, "FltLetterExecutor" character varying) RETURNS TABLE(id bigint, description character varying, "authorId" bigint, file character varying, created_at timestamp without time zone, updated_at timestamp without time zone, "departmentId" bigint, "orderNum" character varying, "deliveryId" smallint, "recorderId" bigint, "baseId" bigint, "baseAssignmentId" bigint, "linkedDocId" bigint, "typeId" smallint, removed timestamp without time zone, name character varying, "creationDate" timestamp without time zone, "closeDate" timestamp without time zone, "coExecutor" character varying, "colName" character varying, "sumContract" character varying, phases text, note character varying, author character varying, "acqDate" timestamp without time zone, customer character varying, addresser character varying, executor bigint, signatory character varying, "letterExecutor" character varying)
    LANGUAGE plpgsql
    AS $$
	BEGIN 	
	UPDATE "documents" 
		SET "updated_at" = current_timestamp,
			"description" = "FltDescription",
			"orderNum" = "FltOrderNum",
			"name" = "FltName",
			"creationDate" = "FltCreationDate",
			"closeDate" = "FltCloseDate",
			"coExecutor" = "FltCoExecutor",
			"colName" = "FltColName",
			"sumContract" = "FltSumContract",
			"phases" = "FltPhases",
			"note" = "FltNote",
			"author" = "FltAuthor",
			"acqDate" = "FltAcqDate",
			"customer" = "FltCustomer",
			"addresser" = "FltAddresser",
			"signatory" = "FltSignatory",
			"executor" = "FltExecutor",
			"letterExecutor" = "FltLetterExecutor"
		WHERE "documents"."id" = "FltDocumentId";
				RETURN QUERY SELECT "documents"."id",
						"documents"."description",
						"documents"."authorId",
						"documents"."file",
						"documents"."created_at",
						"documents"."updated_at",
						"documents"."departmentId",
						"documents"."orderNum",
						"documents"."deliveryId",
						"documents"."recorderId",
						"documents"."baseId",
						"documents"."baseAssignmentId",
						"documents"."linkedDocId",
						"documents"."typeId",
						"documents"."removed",
						"documents"."name",
						"documents"."creationDate",
						"documents"."closeDate",
						"documents"."coExecutor",
						"documents"."colName",
						"documents"."sumContract",
						"documents"."phases",
						"documents"."note",
						"documents"."author",
						"documents"."acqDate",
						"documents"."customer",
						"documents"."addresser",
						"documents"."executor",
						"documents"."signatory",
						"documents"."letterExecutor"
					FROM "documents"
					WHERE "documents"."id" = "FltDocumentId";
	END;
	$$;


ALTER FUNCTION public."UpdateDocumentInfo"("FltDocumentId" bigint, "FltDescription" character varying, "FltOrderNum" character varying, "FltName" character varying, "FltCreationDate" timestamp without time zone, "FltCloseDate" timestamp without time zone, "FltCoExecutor" character varying, "FltColName" character varying, "FltSumContract" character varying, "FltPhases" text, "FltNote" character varying, "FltAuthor" character varying, "FltAcqDate" timestamp without time zone, "FltCustomer" character varying, "FltAddresser" character varying, "FltSignatory" character varying, "FltExecutor" bigint, "FltLetterExecutor" character varying) OWNER TO postgres;

--
-- TOC entry 412 (class 1255 OID 118054)
-- Name: UpdateFileComment(bigint, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."UpdateFileComment"("FltId" bigint, "FltComment" character varying) RETURNS TABLE(id bigint, file character varying, format character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, type smallint, comment character varying)
    LANGUAGE plpgsql
    AS $$
 DECLARE 
 	"Id" bigint;
 BEGIN
 	UPDATE "files" 
		SET "updated_at" = current_timestamp,
			"comment" = "FltComment"
		WHERE "files"."id" = "FltId";
 	RETURN QUERY SELECT "files"."id",
						"files"."file",
						"files"."format",
						"files"."created_at",
						"files"."updated_at",
						"files"."removed",
						"files"."type",
						"files"."comment"
				   FROM "files"
				  WHERE "files"."id" = "FltId";
 END
 $$;


ALTER FUNCTION public."UpdateFileComment"("FltId" bigint, "FltComment" character varying) OWNER TO postgres;

--
-- TOC entry 413 (class 1255 OID 118055)
-- Name: UpdateMailsettingUserWithStatus(bigint, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."UpdateMailsettingUserWithStatus"("FltId" bigint, "FltStatus" boolean) RETURNS TABLE(id bigint, "userId" bigint, "settingId" smallint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, status boolean)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "mailsettings_users" 
		SET "updated_at" = current_timestamp,
			"status" = "FltStatus"
		WHERE "mailsettings_users"."id" = "FltId";		
	RETURN QUERY SELECT "mailsettings_users"."id",
				"mailsettings_users"."userId",
				"mailsettings_users"."settingId",
				"mailsettings_users"."created_at",
				"mailsettings_users"."updated_at",
				"mailsettings_users"."removed",
				"mailsettings_users"."status"
		FROM "mailsettings_users" 
		WHERE "mailsettings_users"."id" = "FltId";
END;
$$;


ALTER FUNCTION public."UpdateMailsettingUserWithStatus"("FltId" bigint, "FltStatus" boolean) OWNER TO postgres;

--
-- TOC entry 414 (class 1255 OID 118056)
-- Name: UpdateMailsettingsUsers(bigint, smallint, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."UpdateMailsettingsUsers"("FltUserId" bigint, "FltSettingId" smallint, "FltStatus" boolean) RETURNS TABLE(id bigint, "userId" bigint, "settingId" smallint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, status boolean)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "mailsettings_users" 
		SET "status" = "FltStatus",
			"updated_at" = current_timestamp
		WHERE "mailsettings_users"."userId" = "FltUserId"
		  AND "mailsettings_users"."settingId" = "FltSettingId";
	RETURN QUERY SELECT "mailsettings_users"."id",
						"mailsettings_users"."userId",
						"mailsettings_users"."settingId",
						"mailsettings_users"."created_at",
						"mailsettings_users"."updated_at",
						"mailsettings_users"."removed",
						"mailsettings_users"."status"
				FROM "mailsettings_users" 
				WHERE "mailsettings_users"."userId" = "FltUserId"
		  		  AND "mailsettings_users"."settingId" = "FltSettingId";
END
$$;


ALTER FUNCTION public."UpdateMailsettingsUsers"("FltUserId" bigint, "FltSettingId" smallint, "FltStatus" boolean) OWNER TO postgres;

--
-- TOC entry 415 (class 1255 OID 118057)
-- Name: UpdatePasswordByUserId(bigint, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."UpdatePasswordByUserId"("FltId" bigint, "FltPassword" character varying) RETURNS TABLE(id bigint, login character varying, surname character varying, firstname character varying, patronymic character varying, department integer, email character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, roleid smallint)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "users" 
		SET "password" = "FltPassword",
			"updated_at" = current_timestamp
		WHERE "users"."id" = "FltId";
	RETURN QUERY SELECT  "users"."id",
						 "users"."login",
						 "users"."surname",
						 "users"."firstname",
						 "users"."patronymic",
						 "users"."department",
						 "users"."email",
						 "users"."created_at",
						 "users"."updated_at",
						 "users"."removed",
						 "users"."roleid"
				FROM "users" WHERE "users"."id" = "FltId";
END
$$;


ALTER FUNCTION public."UpdatePasswordByUserId"("FltId" bigint, "FltPassword" character varying) OWNER TO postgres;

--
-- TOC entry 416 (class 1255 OID 118058)
-- Name: UpdateUser(bigint, character varying, character varying, character varying, character varying, character varying, integer, smallint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."UpdateUser"("FltId" bigint, "FltLogin" character varying, "FltSurname" character varying, "FltFirstname" character varying, "FltPatronymic" character varying, "FltEmail" character varying, "FltDepartment" integer, "FltRoleId" smallint) RETURNS TABLE(id bigint, login character varying, surname character varying, firstname character varying, patronymic character varying, department integer, email character varying, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, roleid smallint)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "users" 
		SET "login" = "FltLogin", 
			"surname" = "FltSurname",
			"firstname" = "FltFirstname", 
			"patronymic" = "FltPatronymic", 
			"department" = "FltDepartment",
			"email" = "FltEmail", 
			"updated_at" = current_timestamp
		WHERE "users"."id" = "FltId";
	RETURN QUERY SELECT  "users"."id",
						 "users"."login",
						 "users"."surname",
						 "users"."firstname",
						 "users"."patronymic",
						 "users"."department",
						 "users"."email",
						 "users"."created_at",
						 "users"."updated_at",
						 "users"."removed",
						 "users"."roleid"
				FROM "users" WHERE "users"."id" = "FltId";
END
$$;


ALTER FUNCTION public."UpdateUser"("FltId" bigint, "FltLogin" character varying, "FltSurname" character varying, "FltFirstname" character varying, "FltPatronymic" character varying, "FltEmail" character varying, "FltDepartment" integer, "FltRoleId" smallint) OWNER TO postgres;

--
-- TOC entry 417 (class 1255 OID 118059)
-- Name: ViewAgreementAndUserById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."ViewAgreementAndUserById"("FltId" bigint) RETURNS TABLE(id bigint, "agreementId" bigint, "userId" bigint, note text, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, refused_at timestamp without time zone, approved_at timestamp without time zone, viewed_at timestamp without time zone, "order" smallint)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "agreements_and_users" 
		SET "viewed_at" = current_timestamp
		WHERE "agreements_and_users"."id" = "FltId";
	RETURN QUERY SELECT "agreements_and_users"."id",
						"agreements_and_users"."agreementId",
						"agreements_and_users"."userId",
						"agreements_and_users"."note",
						"agreements_and_users"."created_at",
						"agreements_and_users"."updated_at",
						"agreements_and_users"."removed",
						"agreements_and_users"."refused_at",
						"agreements_and_users"."approved_at",
						"agreements_and_users"."viewed_at",
						"agreements_and_users"."order"
				FROM "agreements_and_users" 
				WHERE "agreements_and_users"."id" = "FltId";
END
$$;


ALTER FUNCTION public."ViewAgreementAndUserById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 418 (class 1255 OID 118060)
-- Name: ViewAssignmentById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."ViewAssignmentById"("FltId" bigint) RETURNS TABLE(id bigint, "documentId" bigint, "typeId" smallint, "authorId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, text character varying, "executorId" bigint, "baseId" bigint, viewed_at timestamp without time zone, "mainId" bigint)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "assignments" 
		SET "viewed_at" = current_timestamp
		WHERE "assignments"."id" = "FltId";
	RETURN QUERY SELECT "assignments"."id",
							"assignments"."documentId",
							"assignments"."typeId",
							"assignments"."authorId",
							"assignments"."created_at",
							"assignments"."updated_at",
							"assignments"."removed",
							"assignments"."text",
							"assignments"."executorId",
							"assignments"."baseId",
							"assignments"."viewed_at",
							"assignments"."mainId"
					FROM "assignments" WHERE "assignments"."id" = "FltId";
END
$$;


ALTER FUNCTION public."ViewAssignmentById"("FltId" bigint) OWNER TO postgres;

--
-- TOC entry 419 (class 1255 OID 118061)
-- Name: ViewControlById(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."ViewControlById"("FltId" bigint) RETURNS TABLE(id bigint, "userId" bigint, "assignmentId" bigint, created_at timestamp without time zone, updated_at timestamp without time zone, removed timestamp without time zone, viewed_at timestamp without time zone, "initiatorId" bigint)
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE "controls" 
		SET "updated_at" = current_timestamp,
			"viewed_at" = current_timestamp
		WHERE "controls"."id" = "FltId";
 	RETURN QUERY SELECT "controls"."id",
						"controls"."userId",
						"controls"."assignmentId",
						"controls"."created_at",
						"controls"."updated_at",
						"controls"."removed",
						"controls"."viewed_at",
						"controls"."initiatorId"
				   FROM "controls"
				  WHERE "controls"."id" = "FltId";
END
$$;


ALTER FUNCTION public."ViewControlById"("FltId" bigint) OWNER TO postgres;

SET default_tablespace = '';

--
-- TOC entry 196 (class 1259 OID 118062)
-- Name: acquaintances; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.acquaintances (
    id bigint NOT NULL,
    "documentId" bigint NOT NULL,
    "userId" bigint NOT NULL,
    "initiatorId" bigint NOT NULL,
    seen_at timestamp without time zone,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.acquaintances OWNER TO postgres;

--
-- TOC entry 197 (class 1259 OID 118065)
-- Name: Acquaintances_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Acquaintances_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public."Acquaintances_id_seq" OWNER TO postgres;

--
-- TOC entry 3547 (class 0 OID 0)
-- Dependencies: 197
-- Name: Acquaintances_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Acquaintances_id_seq" OWNED BY public.acquaintances.id;


--
-- TOC entry 198 (class 1259 OID 118067)
-- Name: User; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."User" (
    id bigint,
    "agreementId" bigint,
    "userId" bigint,
    note character varying,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public."User" OWNER TO postgres;

--
-- TOC entry 199 (class 1259 OID 118073)
-- Name: additions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.additions (
    id bigint NOT NULL,
    "fileId" bigint,
    comment character varying(512),
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.additions OWNER TO postgres;

--
-- TOC entry 200 (class 1259 OID 118079)
-- Name: agreements; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.agreements (
    id bigint NOT NULL,
    "documentId" bigint NOT NULL,
    agreed_at timestamp without time zone,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone,
    refused_at timestamp without time zone,
    deadline timestamp without time zone
);


ALTER TABLE public.agreements OWNER TO postgres;

--
-- TOC entry 201 (class 1259 OID 118083)
-- Name: agreements_and_users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.agreements_and_users (
    id bigint NOT NULL,
    "agreementId" bigint NOT NULL,
    "userId" bigint NOT NULL,
    note text,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    removed timestamp without time zone,
    refused_at timestamp without time zone,
    approved_at timestamp without time zone,
    viewed_at timestamp without time zone,
    "order" smallint
);


ALTER TABLE public.agreements_and_users OWNER TO postgres;

--
-- TOC entry 202 (class 1259 OID 118089)
-- Name: agreements_and_users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.agreements_and_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.agreements_and_users_id_seq OWNER TO postgres;

--
-- TOC entry 3548 (class 0 OID 0)
-- Dependencies: 202
-- Name: agreements_and_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.agreements_and_users_id_seq OWNED BY public.agreements_and_users.id;


--
-- TOC entry 203 (class 1259 OID 118091)
-- Name: agreements_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.agreements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.agreements_id_seq OWNER TO postgres;

--
-- TOC entry 3549 (class 0 OID 0)
-- Dependencies: 203
-- Name: agreements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.agreements_id_seq OWNED BY public.agreements.id;


--
-- TOC entry 204 (class 1259 OID 118093)
-- Name: apps; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.apps (
    id bigint NOT NULL,
    "documentId" bigint,
    "assignmentId" bigint,
    "reportId" bigint,
    title character varying(64) NOT NULL,
    file character varying(128),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.apps OWNER TO postgres;

--
-- TOC entry 205 (class 1259 OID 118097)
-- Name: apps_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.apps_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.apps_id_seq OWNER TO postgres;

--
-- TOC entry 3550 (class 0 OID 0)
-- Dependencies: 205
-- Name: apps_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.apps_id_seq OWNED BY public.apps.id;


--
-- TOC entry 206 (class 1259 OID 118099)
-- Name: assignment_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.assignment_types (
    id smallint NOT NULL,
    title character varying(64) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.assignment_types OWNER TO postgres;

--
-- TOC entry 207 (class 1259 OID 118103)
-- Name: assignments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.assignments (
    id bigint NOT NULL,
    "documentId" bigint,
    "typeId" smallint NOT NULL,
    "authorId" bigint NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    completed_at timestamp without time zone,
    removed timestamp without time zone,
    text character varying(512),
    "executorId" bigint,
    "baseId" bigint,
    viewed_at timestamp without time zone,
    refused_at timestamp without time zone,
    "mainId" bigint,
    description text
);


ALTER TABLE public.assignments OWNER TO postgres;

--
-- TOC entry 208 (class 1259 OID 118110)
-- Name: assignments_and_assignmentstatuses; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.assignments_and_assignmentstatuses (
    id bigint NOT NULL,
    "assignmentId" bigint NOT NULL,
    "assignmentstatusId" smallint NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone,
    note character varying(512)
);


ALTER TABLE public.assignments_and_assignmentstatuses OWNER TO postgres;

--
-- TOC entry 209 (class 1259 OID 118117)
-- Name: assignments_and_notestatuses_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.assignments_and_notestatuses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.assignments_and_notestatuses_id_seq OWNER TO postgres;

--
-- TOC entry 3551 (class 0 OID 0)
-- Dependencies: 209
-- Name: assignments_and_notestatuses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.assignments_and_notestatuses_id_seq OWNED BY public.assignments_and_assignmentstatuses.id;


--
-- TOC entry 210 (class 1259 OID 118119)
-- Name: assignments_deadlines; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.assignments_deadlines (
    id bigint NOT NULL,
    "assignmentId" bigint NOT NULL,
    "initiatorId" bigint NOT NULL,
    "approvedUserId" bigint,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    deadline timestamp without time zone NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone,
    initiated_at timestamp without time zone NOT NULL,
    approved_at timestamp without time zone,
    refused_at timestamp without time zone,
    comment character varying(255),
    "fileId" bigint
);


ALTER TABLE public.assignments_deadlines OWNER TO postgres;

--
-- TOC entry 211 (class 1259 OID 118123)
-- Name: assignments_deadlines_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.assignments_deadlines_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.assignments_deadlines_id_seq OWNER TO postgres;

--
-- TOC entry 3552 (class 0 OID 0)
-- Dependencies: 211
-- Name: assignments_deadlines_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.assignments_deadlines_id_seq OWNED BY public.assignments_deadlines.id;


--
-- TOC entry 212 (class 1259 OID 118125)
-- Name: assignments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.assignments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.assignments_id_seq OWNER TO postgres;

--
-- TOC entry 213 (class 1259 OID 118127)
-- Name: assignments_id_seq1; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.assignments_id_seq1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.assignments_id_seq1 OWNER TO postgres;

--
-- TOC entry 3553 (class 0 OID 0)
-- Dependencies: 213
-- Name: assignments_id_seq1; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.assignments_id_seq1 OWNED BY public.assignments.id;


--
-- TOC entry 214 (class 1259 OID 118129)
-- Name: blog; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.blog (
    id bigint NOT NULL,
    title character varying(64) NOT NULL,
    text character varying(512),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.blog OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 118136)
-- Name: blog_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.blog_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.blog_id_seq OWNER TO postgres;

--
-- TOC entry 3554 (class 0 OID 0)
-- Dependencies: 215
-- Name: blog_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.blog_id_seq OWNED BY public.blog.id;


--
-- TOC entry 216 (class 1259 OID 118138)
-- Name: contract_attributes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contract_attributes (
    id bigint NOT NULL,
    "documentId" bigint NOT NULL,
    price numeric,
    deadline timestamp without time zone,
    ordernum integer,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.contract_attributes OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 118145)
-- Name: contract_attributes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.contract_attributes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.contract_attributes_id_seq OWNER TO postgres;

--
-- TOC entry 3555 (class 0 OID 0)
-- Dependencies: 217
-- Name: contract_attributes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.contract_attributes_id_seq OWNED BY public.contract_attributes.id;


--
-- TOC entry 218 (class 1259 OID 118147)
-- Name: controls; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.controls (
    id bigint NOT NULL,
    "userId" bigint NOT NULL,
    "assignmentId" bigint,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone,
    viewed_at timestamp without time zone,
    "initiatorId" bigint,
    "documentId" bigint
);


ALTER TABLE public.controls OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 118150)
-- Name: control_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.control_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.control_id_seq OWNER TO postgres;

--
-- TOC entry 3556 (class 0 OID 0)
-- Dependencies: 219
-- Name: control_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.control_id_seq OWNED BY public.controls.id;


--
-- TOC entry 220 (class 1259 OID 118152)
-- Name: counterparties; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.counterparties (
    id bigint NOT NULL,
    "documentId" bigint NOT NULL,
    "userId" bigint,
    outerpartner character varying(64),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.counterparties OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 118156)
-- Name: counterparties_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.counterparties_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.counterparties_id_seq OWNER TO postgres;

--
-- TOC entry 3557 (class 0 OID 0)
-- Dependencies: 221
-- Name: counterparties_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.counterparties_id_seq OWNED BY public.counterparties.id;


--
-- TOC entry 222 (class 1259 OID 118158)
-- Name: deadlines; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.deadlines (
    id bigint NOT NULL,
    "documentId" bigint NOT NULL,
    "initiatorId" bigint NOT NULL,
    "approvedUserId" bigint NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    approved_at timestamp without time zone,
    "newDate" timestamp without time zone NOT NULL,
    removed timestamp without time zone
);


ALTER TABLE public.deadlines OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 118162)
-- Name: delivery_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.delivery_types (
    id smallint NOT NULL,
    title character varying(64) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.delivery_types OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 118166)
-- Name: delivery_types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.delivery_types_id_seq
    AS smallint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.delivery_types_id_seq OWNER TO postgres;

--
-- TOC entry 3558 (class 0 OID 0)
-- Dependencies: 224
-- Name: delivery_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.delivery_types_id_seq OWNED BY public.delivery_types.id;


--
-- TOC entry 225 (class 1259 OID 118168)
-- Name: departments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.departments (
    id integer NOT NULL,
    code character varying(36),
    title character varying(64) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone,
    "headId" bigint
);


ALTER TABLE public.departments OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 118172)
-- Name: departments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.departments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.departments_id_seq OWNER TO postgres;

--
-- TOC entry 3559 (class 0 OID 0)
-- Dependencies: 226
-- Name: departments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.departments_id_seq OWNED BY public.departments.id;


--
-- TOC entry 227 (class 1259 OID 118174)
-- Name: dirusers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dirusers (
    id bigint NOT NULL,
    surname character varying(64) NOT NULL,
    firstname character varying(64),
    patronymic character varying(64),
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone,
    "departmentId" integer
);


ALTER TABLE public.dirusers OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 118177)
-- Name: dirusers_and_documents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dirusers_and_documents (
    id bigint NOT NULL,
    "diruserId" bigint NOT NULL,
    "documentId" bigint NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.dirusers_and_documents OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 118180)
-- Name: dirusers_and_documents_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.dirusers_and_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.dirusers_and_documents_id_seq OWNER TO postgres;

--
-- TOC entry 3560 (class 0 OID 0)
-- Dependencies: 229
-- Name: dirusers_and_documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.dirusers_and_documents_id_seq OWNED BY public.dirusers_and_documents.id;


--
-- TOC entry 230 (class 1259 OID 118182)
-- Name: dirusers_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.dirusers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.dirusers_id_seq OWNER TO postgres;

--
-- TOC entry 3561 (class 0 OID 0)
-- Dependencies: 230
-- Name: dirusers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.dirusers_id_seq OWNED BY public.dirusers.id;


--
-- TOC entry 231 (class 1259 OID 118184)
-- Name: document_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.document_types (
    id smallint NOT NULL,
    title character varying(64) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.document_types OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 118188)
-- Name: document_types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.document_types_id_seq
    AS smallint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.document_types_id_seq OWNER TO postgres;

--
-- TOC entry 3562 (class 0 OID 0)
-- Dependencies: 232
-- Name: document_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.document_types_id_seq OWNED BY public.document_types.id;


--
-- TOC entry 233 (class 1259 OID 118190)
-- Name: documents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.documents (
    id bigint NOT NULL,
    description character varying(512) NOT NULL,
    "authorId" bigint NOT NULL,
    file character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    "departmentId" bigint,
    "deliveryId" smallint,
    "recorderId" bigint NOT NULL,
    "baseId" bigint,
    "baseAssignmentId" bigint,
    "linkedDocId" bigint,
    "typeId" smallint NOT NULL,
    removed timestamp without time zone,
    name character varying(128),
    "creationDate" timestamp without time zone,
    "closeDate" timestamp without time zone,
    "coExecutor" character varying(128),
    "colName" character varying(128),
    "sumContract" character varying(64),
    phases text,
    note character varying(128),
    author character varying(128),
    "acqDate" timestamp without time zone,
    customer character varying(128),
    addresser character varying(128),
    signatory character varying(128),
    executor bigint,
    "letterExecutor" character varying(128),
    "orderNum" character varying(32)
);


ALTER TABLE public.documents OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 118197)
-- Name: documents_and_docstatuses; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.documents_and_docstatuses (
    id bigint NOT NULL,
    "documentId" bigint NOT NULL,
    "docstatusId" smallint NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.documents_and_docstatuses OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 118201)
-- Name: documents_and_docstatuses_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.documents_and_docstatuses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.documents_and_docstatuses_id_seq OWNER TO postgres;

--
-- TOC entry 3563 (class 0 OID 0)
-- Dependencies: 235
-- Name: documents_and_docstatuses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.documents_and_docstatuses_id_seq OWNED BY public.documents_and_docstatuses.id;


--
-- TOC entry 236 (class 1259 OID 118203)
-- Name: documents_and_files; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.documents_and_files (
    id bigint NOT NULL,
    "documentId" bigint NOT NULL,
    "fileId" bigint NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.documents_and_files OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 118207)
-- Name: documents_and_files_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.documents_and_files_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.documents_and_files_id_seq OWNER TO postgres;

--
-- TOC entry 3564 (class 0 OID 0)
-- Dependencies: 237
-- Name: documents_and_files_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.documents_and_files_id_seq OWNED BY public.documents_and_files.id;


--
-- TOC entry 238 (class 1259 OID 118209)
-- Name: documents_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.documents_id_seq OWNER TO postgres;

--
-- TOC entry 3565 (class 0 OID 0)
-- Dependencies: 238
-- Name: documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.documents_id_seq OWNED BY public.documents.id;


--
-- TOC entry 239 (class 1259 OID 118211)
-- Name: executors; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.executors (
    id bigint NOT NULL,
    "documentId" bigint NOT NULL,
    "userId" bigint NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.executors OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 118215)
-- Name: executors_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.executors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.executors_id_seq OWNER TO postgres;

--
-- TOC entry 3566 (class 0 OID 0)
-- Dependencies: 240
-- Name: executors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.executors_id_seq OWNED BY public.executors.id;


--
-- TOC entry 241 (class 1259 OID 118217)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- TOC entry 242 (class 1259 OID 118224)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- TOC entry 3567 (class 0 OID 0)
-- Dependencies: 242
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 243 (class 1259 OID 118226)
-- Name: feedbacks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.feedbacks (
    id bigint NOT NULL,
    "userId" bigint NOT NULL,
    title character varying(64) NOT NULL,
    text character varying(512) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    completed_at timestamp without time zone,
    removed timestamp without time zone,
    "baseId" bigint,
    viewed_at timestamp without time zone
);


ALTER TABLE public.feedbacks OWNER TO postgres;

--
-- TOC entry 244 (class 1259 OID 118233)
-- Name: feedbacks_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.feedbacks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.feedbacks_id_seq OWNER TO postgres;

--
-- TOC entry 3568 (class 0 OID 0)
-- Dependencies: 244
-- Name: feedbacks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.feedbacks_id_seq OWNED BY public.feedbacks.id;


--
-- TOC entry 245 (class 1259 OID 118235)
-- Name: files; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.files (
    id bigint NOT NULL,
    file character varying(256) NOT NULL,
    format character varying(32) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone,
    type smallint NOT NULL,
    comment character varying(256)
);


ALTER TABLE public.files OWNER TO postgres;

--
-- TOC entry 246 (class 1259 OID 118241)
-- Name: files_and_additions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.files_and_additions (
    id bigint NOT NULL,
    "fileId" bigint NOT NULL,
    "documentId" bigint,
    "assignmentId" bigint,
    "feedbackId" bigint,
    "blogId" bigint,
    "agreementAndUserId" bigint,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.files_and_additions OWNER TO postgres;

--
-- TOC entry 247 (class 1259 OID 118244)
-- Name: files_and_adds_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.files_and_adds_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.files_and_adds_id_seq OWNER TO postgres;

--
-- TOC entry 3569 (class 0 OID 0)
-- Dependencies: 247
-- Name: files_and_adds_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.files_and_adds_id_seq OWNED BY public.files_and_additions.id;


--
-- TOC entry 248 (class 1259 OID 118246)
-- Name: files_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.files_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.files_id_seq OWNER TO postgres;

--
-- TOC entry 3570 (class 0 OID 0)
-- Dependencies: 248
-- Name: files_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.files_id_seq OWNED BY public.files.id;


--
-- TOC entry 249 (class 1259 OID 118248)
-- Name: mailsettings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mailsettings (
    id smallint NOT NULL,
    title character varying(64) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.mailsettings OWNER TO postgres;

--
-- TOC entry 250 (class 1259 OID 118252)
-- Name: mailsettings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.mailsettings_id_seq
    AS smallint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.mailsettings_id_seq OWNER TO postgres;

--
-- TOC entry 3571 (class 0 OID 0)
-- Dependencies: 250
-- Name: mailsettings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.mailsettings_id_seq OWNED BY public.mailsettings.id;


--
-- TOC entry 251 (class 1259 OID 118254)
-- Name: mailsettings_users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mailsettings_users (
    id bigint NOT NULL,
    "userId" bigint NOT NULL,
    "settingId" smallint NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone,
    status boolean
);


ALTER TABLE public.mailsettings_users OWNER TO postgres;

--
-- TOC entry 252 (class 1259 OID 118257)
-- Name: mailsettings_users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.mailsettings_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.mailsettings_users_id_seq OWNER TO postgres;

--
-- TOC entry 3572 (class 0 OID 0)
-- Dependencies: 252
-- Name: mailsettings_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.mailsettings_users_id_seq OWNED BY public.mailsettings_users.id;


--
-- TOC entry 253 (class 1259 OID 118259)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 254 (class 1259 OID 118262)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- TOC entry 3573 (class 0 OID 0)
-- Dependencies: 254
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 255 (class 1259 OID 118264)
-- Name: password_resets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_resets OWNER TO postgres;

--
-- TOC entry 256 (class 1259 OID 118270)
-- Name: report_agreements; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.report_agreements (
    id bigint NOT NULL,
    "reportId" bigint NOT NULL,
    "userId" bigint NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    agreed_at timestamp without time zone,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.report_agreements OWNER TO postgres;

--
-- TOC entry 257 (class 1259 OID 118274)
-- Name: report_agreements_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.report_agreements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.report_agreements_id_seq OWNER TO postgres;

--
-- TOC entry 3574 (class 0 OID 0)
-- Dependencies: 257
-- Name: report_agreements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.report_agreements_id_seq OWNED BY public.report_agreements.id;


--
-- TOC entry 258 (class 1259 OID 118276)
-- Name: reports; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.reports (
    id bigint NOT NULL,
    "documentId" bigint NOT NULL,
    text character varying(255) NOT NULL,
    "authorId" bigint NOT NULL,
    "assignmentId" bigint,
    "appId" bigint,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.reports OWNER TO postgres;

--
-- TOC entry 259 (class 1259 OID 118280)
-- Name: reports_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.reports_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.reports_id_seq OWNER TO postgres;

--
-- TOC entry 3575 (class 0 OID 0)
-- Dependencies: 259
-- Name: reports_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.reports_id_seq OWNED BY public.reports.id;


--
-- TOC entry 260 (class 1259 OID 118282)
-- Name: responsibles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.responsibles (
    id bigint NOT NULL,
    "assignmentId" bigint NOT NULL,
    "userId" bigint NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.responsibles OWNER TO postgres;

--
-- TOC entry 261 (class 1259 OID 118286)
-- Name: responsibles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.responsibles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.responsibles_id_seq OWNER TO postgres;

--
-- TOC entry 3576 (class 0 OID 0)
-- Dependencies: 261
-- Name: responsibles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.responsibles_id_seq OWNED BY public.responsibles.id;


--
-- TOC entry 262 (class 1259 OID 118288)
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    id smallint NOT NULL,
    title character varying(32) NOT NULL,
    slug character varying(32) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- TOC entry 263 (class 1259 OID 118292)
-- Name: statuses; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.statuses (
    id smallint NOT NULL,
    title character varying(64) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone,
    alias character varying(64),
    "group" smallint
);


ALTER TABLE public.statuses OWNER TO postgres;

--
-- TOC entry 264 (class 1259 OID 118296)
-- Name: text_timezone; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.text_timezone (
    id bigint NOT NULL,
    "time" timestamp without time zone NOT NULL
);


ALTER TABLE public.text_timezone OWNER TO postgres;

--
-- TOC entry 265 (class 1259 OID 118299)
-- Name: text_timezone_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.text_timezone_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.text_timezone_id_seq OWNER TO postgres;

--
-- TOC entry 3577 (class 0 OID 0)
-- Dependencies: 265
-- Name: text_timezone_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.text_timezone_id_seq OWNED BY public.text_timezone.id;


--
-- TOC entry 266 (class 1259 OID 118301)
-- Name: userpositions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.userpositions (
    id smallint NOT NULL,
    title character varying(64) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.userpositions OWNER TO postgres;

--
-- TOC entry 267 (class 1259 OID 118305)
-- Name: userpositions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.userpositions_id_seq
    AS smallint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.userpositions_id_seq OWNER TO postgres;

--
-- TOC entry 3578 (class 0 OID 0)
-- Dependencies: 267
-- Name: userpositions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.userpositions_id_seq OWNED BY public.userpositions.id;


--
-- TOC entry 268 (class 1259 OID 118307)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 269 (class 1259 OID 118309)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint DEFAULT nextval('public.users_id_seq'::regclass) NOT NULL,
    login character varying(64) NOT NULL,
    surname character varying(64),
    firstname character varying(64),
    patronymic character varying(64),
    department integer,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    removed timestamp(0) without time zone,
    roleid smallint DEFAULT 2
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 270 (class 1259 OID 118317)
-- Name: users_and_departments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users_and_departments (
    id bigint NOT NULL,
    "userId" bigint NOT NULL,
    "departmentId" bigint NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.users_and_departments OWNER TO postgres;

--
-- TOC entry 271 (class 1259 OID 118320)
-- Name: users_and_departments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_and_departments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_and_departments_id_seq OWNER TO postgres;

--
-- TOC entry 3579 (class 0 OID 0)
-- Dependencies: 271
-- Name: users_and_departments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_and_departments_id_seq OWNED BY public.users_and_departments.id;


--
-- TOC entry 272 (class 1259 OID 118322)
-- Name: users_and_userpositions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users_and_userpositions (
    id bigint NOT NULL,
    "userId" bigint NOT NULL,
    "positionId" bigint NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    removed timestamp without time zone
);


ALTER TABLE public.users_and_userpositions OWNER TO postgres;

--
-- TOC entry 273 (class 1259 OID 118326)
-- Name: users_and_userpositions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_and_userpositions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_and_userpositions_id_seq OWNER TO postgres;

--
-- TOC entry 3580 (class 0 OID 0)
-- Dependencies: 273
-- Name: users_and_userpositions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_and_userpositions_id_seq OWNED BY public.users_and_userpositions.id;


--
-- TOC entry 3131 (class 2604 OID 118328)
-- Name: acquaintances id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.acquaintances ALTER COLUMN id SET DEFAULT nextval('public."Acquaintances_id_seq"'::regclass);


--
-- TOC entry 3132 (class 2604 OID 118329)
-- Name: agreements id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.agreements ALTER COLUMN id SET DEFAULT nextval('public.agreements_id_seq'::regclass);


--
-- TOC entry 3134 (class 2604 OID 118330)
-- Name: agreements_and_users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.agreements_and_users ALTER COLUMN id SET DEFAULT nextval('public.agreements_and_users_id_seq'::regclass);


--
-- TOC entry 3135 (class 2604 OID 118331)
-- Name: apps id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.apps ALTER COLUMN id SET DEFAULT nextval('public.apps_id_seq'::regclass);


--
-- TOC entry 3138 (class 2604 OID 118332)
-- Name: assignments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments ALTER COLUMN id SET DEFAULT nextval('public.assignments_id_seq1'::regclass);


--
-- TOC entry 3140 (class 2604 OID 118333)
-- Name: assignments_and_assignmentstatuses id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments_and_assignmentstatuses ALTER COLUMN id SET DEFAULT nextval('public.assignments_and_notestatuses_id_seq'::regclass);


--
-- TOC entry 3142 (class 2604 OID 118334)
-- Name: assignments_deadlines id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments_deadlines ALTER COLUMN id SET DEFAULT nextval('public.assignments_deadlines_id_seq'::regclass);


--
-- TOC entry 3144 (class 2604 OID 118335)
-- Name: blog id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blog ALTER COLUMN id SET DEFAULT nextval('public.blog_id_seq'::regclass);


--
-- TOC entry 3146 (class 2604 OID 118336)
-- Name: contract_attributes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contract_attributes ALTER COLUMN id SET DEFAULT nextval('public.contract_attributes_id_seq'::regclass);


--
-- TOC entry 3148 (class 2604 OID 118337)
-- Name: controls id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.controls ALTER COLUMN id SET DEFAULT nextval('public.control_id_seq'::regclass);


--
-- TOC entry 3149 (class 2604 OID 118338)
-- Name: counterparties id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.counterparties ALTER COLUMN id SET DEFAULT nextval('public.counterparties_id_seq'::regclass);


--
-- TOC entry 3152 (class 2604 OID 118339)
-- Name: delivery_types id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.delivery_types ALTER COLUMN id SET DEFAULT nextval('public.delivery_types_id_seq'::regclass);


--
-- TOC entry 3154 (class 2604 OID 118340)
-- Name: departments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.departments ALTER COLUMN id SET DEFAULT nextval('public.departments_id_seq'::regclass);


--
-- TOC entry 3156 (class 2604 OID 118341)
-- Name: dirusers id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dirusers ALTER COLUMN id SET DEFAULT nextval('public.dirusers_id_seq'::regclass);


--
-- TOC entry 3157 (class 2604 OID 118342)
-- Name: dirusers_and_documents id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dirusers_and_documents ALTER COLUMN id SET DEFAULT nextval('public.dirusers_and_documents_id_seq'::regclass);


--
-- TOC entry 3158 (class 2604 OID 118343)
-- Name: document_types id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.document_types ALTER COLUMN id SET DEFAULT nextval('public.document_types_id_seq'::regclass);


--
-- TOC entry 3160 (class 2604 OID 118344)
-- Name: documents id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.documents ALTER COLUMN id SET DEFAULT nextval('public.documents_id_seq'::regclass);


--
-- TOC entry 3162 (class 2604 OID 118345)
-- Name: documents_and_docstatuses id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.documents_and_docstatuses ALTER COLUMN id SET DEFAULT nextval('public.documents_and_docstatuses_id_seq'::regclass);


--
-- TOC entry 3164 (class 2604 OID 118346)
-- Name: documents_and_files id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.documents_and_files ALTER COLUMN id SET DEFAULT nextval('public.documents_and_files_id_seq'::regclass);


--
-- TOC entry 3166 (class 2604 OID 118347)
-- Name: executors id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.executors ALTER COLUMN id SET DEFAULT nextval('public.executors_id_seq'::regclass);


--
-- TOC entry 3168 (class 2604 OID 118348)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 3170 (class 2604 OID 118349)
-- Name: feedbacks id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.feedbacks ALTER COLUMN id SET DEFAULT nextval('public.feedbacks_id_seq'::regclass);


--
-- TOC entry 3172 (class 2604 OID 118350)
-- Name: files id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.files ALTER COLUMN id SET DEFAULT nextval('public.files_id_seq'::regclass);


--
-- TOC entry 3173 (class 2604 OID 118351)
-- Name: files_and_additions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.files_and_additions ALTER COLUMN id SET DEFAULT nextval('public.files_and_adds_id_seq'::regclass);


--
-- TOC entry 3174 (class 2604 OID 118352)
-- Name: mailsettings id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mailsettings ALTER COLUMN id SET DEFAULT nextval('public.mailsettings_id_seq'::regclass);


--
-- TOC entry 3176 (class 2604 OID 118353)
-- Name: mailsettings_users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mailsettings_users ALTER COLUMN id SET DEFAULT nextval('public.mailsettings_users_id_seq'::regclass);


--
-- TOC entry 3177 (class 2604 OID 118354)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 3178 (class 2604 OID 118355)
-- Name: report_agreements id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.report_agreements ALTER COLUMN id SET DEFAULT nextval('public.report_agreements_id_seq'::regclass);


--
-- TOC entry 3180 (class 2604 OID 118356)
-- Name: reports id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reports ALTER COLUMN id SET DEFAULT nextval('public.reports_id_seq'::regclass);


--
-- TOC entry 3182 (class 2604 OID 118357)
-- Name: responsibles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.responsibles ALTER COLUMN id SET DEFAULT nextval('public.responsibles_id_seq'::regclass);


--
-- TOC entry 3186 (class 2604 OID 118358)
-- Name: text_timezone id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.text_timezone ALTER COLUMN id SET DEFAULT nextval('public.text_timezone_id_seq'::regclass);


--
-- TOC entry 3187 (class 2604 OID 118359)
-- Name: userpositions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.userpositions ALTER COLUMN id SET DEFAULT nextval('public.userpositions_id_seq'::regclass);


--
-- TOC entry 3191 (class 2604 OID 118360)
-- Name: users_and_departments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_and_departments ALTER COLUMN id SET DEFAULT nextval('public.users_and_departments_id_seq'::regclass);


--
-- TOC entry 3192 (class 2604 OID 118361)
-- Name: users_and_userpositions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_and_userpositions ALTER COLUMN id SET DEFAULT nextval('public.users_and_userpositions_id_seq'::regclass);


--
-- TOC entry 3465 (class 0 OID 118067)
-- Dependencies: 198
-- Data for Name: User; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."User" (id, "agreementId", "userId", note, created_at, updated_at, removed) FROM stdin;
2	2	5	\N	2021-06-26 17:56:40.246298	\N	\N
-- \.


--
-- TOC entry 3463 (class 0 OID 118062)
-- Dependencies: 196
-- Data for Name: acquaintances; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.acquaintances (id, "documentId", "userId", "initiatorId", seen_at, created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3466 (class 0 OID 118073)
-- Dependencies: 199
-- Data for Name: additions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.additions (id, "fileId", comment, created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3467 (class 0 OID 118079)
-- Dependencies: 200
-- Data for Name: agreements; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.agreements (id, "documentId", agreed_at, created_at, updated_at, removed, refused_at, deadline) FROM stdin;
-- \.


--
-- TOC entry 3468 (class 0 OID 118083)
-- Dependencies: 201
-- Data for Name: agreements_and_users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.agreements_and_users (id, "agreementId", "userId", note, created_at, updated_at, removed, refused_at, approved_at, viewed_at, "order") FROM stdin;
-- \.


--
-- TOC entry 3471 (class 0 OID 118093)
-- Dependencies: 204
-- Data for Name: apps; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.apps (id, "documentId", "assignmentId", "reportId", title, file, created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3473 (class 0 OID 118099)
-- Dependencies: 206
-- Data for Name: assignment_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.assignment_types (id, title, created_at, updated_at, removed) FROM stdin;
1	Срочное	2021-10-15 14:51:33.489593	\N	\N
2	Важное	2021-10-15 14:51:33.489593	\N	\N
3	Обычное	2021-11-29 12:37:23.589145	\N	\N
4	Не срочное	2021-11-29 12:37:23.589145	\N	\N
-- \.


--
-- TOC entry 3474 (class 0 OID 118103)
-- Dependencies: 207
-- Data for Name: assignments; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.assignments (id, "documentId", "typeId", "authorId", created_at, updated_at, completed_at, removed, text, "executorId", "baseId", viewed_at, refused_at, "mainId", description) FROM stdin;
-- \.


--
-- TOC entry 3475 (class 0 OID 118110)
-- Dependencies: 208
-- Data for Name: assignments_and_assignmentstatuses; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.assignments_and_assignmentstatuses (id, "assignmentId", "assignmentstatusId", created_at, updated_at, removed, note) FROM stdin;
-- \.


--
-- TOC entry 3477 (class 0 OID 118119)
-- Dependencies: 210
-- Data for Name: assignments_deadlines; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.assignments_deadlines (id, "assignmentId", "initiatorId", "approvedUserId", created_at, deadline, updated_at, removed, initiated_at, approved_at, refused_at, comment, "fileId") FROM stdin;
-- \.


--
-- TOC entry 3481 (class 0 OID 118129)
-- Dependencies: 214
-- Data for Name: blog; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.blog (id, title, text, created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3483 (class 0 OID 118138)
-- Dependencies: 216
-- Data for Name: contract_attributes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.contract_attributes (id, "documentId", price, deadline, ordernum, created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3485 (class 0 OID 118147)
-- Dependencies: 218
-- Data for Name: controls; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.controls (id, "userId", "assignmentId", created_at, updated_at, removed, viewed_at, "initiatorId", "documentId") FROM stdin;
-- \.


--
-- TOC entry 3487 (class 0 OID 118152)
-- Dependencies: 220
-- Data for Name: counterparties; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.counterparties (id, "documentId", "userId", outerpartner, created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3489 (class 0 OID 118158)
-- Dependencies: 222
-- Data for Name: deadlines; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.deadlines (id, "documentId", "initiatorId", "approvedUserId", created_at, approved_at, "newDate", removed) FROM stdin;
-- \.


--
-- TOC entry 3490 (class 0 OID 118162)
-- Dependencies: 223
-- Data for Name: delivery_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.delivery_types (id, title, created_at, updated_at, removed) FROM stdin;
1	Почта	2021-06-16 10:52:25.112092	\N	\N
2	Электронная почта	2021-06-16 10:52:25.112092	\N	\N
3	Курьер	2021-06-16 10:52:25.112092	\N	\N
-- \.


--
-- TOC entry 3492 (class 0 OID 118168)
-- Dependencies: 225
-- Data for Name: departments; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.departments (id, code, title, created_at, updated_at, removed, "headId") FROM stdin;
-- \.


--
-- TOC entry 3494 (class 0 OID 118174)
-- Dependencies: 227
-- Data for Name: dirusers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.dirusers (id, surname, firstname, patronymic, created_at, updated_at, removed, "departmentId") FROM stdin;
-- \.


--
-- TOC entry 3495 (class 0 OID 118177)
-- Dependencies: 228
-- Data for Name: dirusers_and_documents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.dirusers_and_documents (id, "diruserId", "documentId", created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3498 (class 0 OID 118184)
-- Dependencies: 231
-- Data for Name: document_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.document_types (id, title, created_at, updated_at, removed) FROM stdin;
1	Служебная записка	2021-06-15 17:57:13.818256	\N	\N
2	Договор	2021-06-16 13:28:42.794684	\N	\N
4	Прочее	2021-09-27 18:51:30.255934	\N	\N
6	Входящее письмо	2021-10-18 18:18:57.743618	\N	\N
12	Уведомление	2022-06-09 09:23:08.779796	\N	\N
9	Приказ по организационной деятельности	2022-06-09 09:23:08.779796	\N	\N
7	Исходящее письмо	2021-12-21 13:21:03.162982	\N	\N
13	Организационно-распорядительный документ	2022-06-10 09:46:26.061102	\N	\N
3	Докладная записка	2021-06-16 13:28:42.794684	\N	2022-07-01 15:28:42.794684
8	Входящее внутреннее письмо	2022-06-09 09:23:08.779796	\N	2022-07-01 15:28:42.794684
10	Приказ об утверждении штатного расписания	2022-06-09 09:23:08.779796	\N	2022-07-01 15:28:42.794684
11	Доверенность	2022-06-09 09:23:08.779796	\N	2022-07-01 15:28:42.794684
14	Дополнительное соглашение	2024-07-03 08:50:42.037193	\N	\N
15	Соглашение о неразглашении конфиденциальной информации	2024-07-03 08:51:06.107341	\N	\N
-- \.


--
-- TOC entry 3500 (class 0 OID 118190)
-- Dependencies: 233
-- Data for Name: documents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.documents (id, description, "authorId", file, created_at, updated_at, "departmentId", "deliveryId", "recorderId", "baseId", "baseAssignmentId", "linkedDocId", "typeId", removed, name, "creationDate", "closeDate", "coExecutor", "colName", "sumContract", phases, note, author, "acqDate", customer, addresser, signatory, executor, "letterExecutor", "orderNum") FROM stdin;
-- \.


--
-- TOC entry 3501 (class 0 OID 118197)
-- Dependencies: 234
-- Data for Name: documents_and_docstatuses; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.documents_and_docstatuses (id, "documentId", "docstatusId", created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3503 (class 0 OID 118203)
-- Dependencies: 236
-- Data for Name: documents_and_files; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.documents_and_files (id, "documentId", "fileId", created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3506 (class 0 OID 118211)
-- Dependencies: 239
-- Data for Name: executors; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.executors (id, "documentId", "userId", created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3508 (class 0 OID 118217)
-- Dependencies: 241
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
-- \.


--
-- TOC entry 3510 (class 0 OID 118226)
-- Dependencies: 243
-- Data for Name: feedbacks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.feedbacks (id, "userId", title, text, created_at, updated_at, completed_at, removed, "baseId", viewed_at) FROM stdin;
-- \.


--
-- TOC entry 3512 (class 0 OID 118235)
-- Dependencies: 245
-- Data for Name: files; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.files (id, file, format, created_at, updated_at, removed, type, comment) FROM stdin;
-- \.


--
-- TOC entry 3513 (class 0 OID 118241)
-- Dependencies: 246
-- Data for Name: files_and_additions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.files_and_additions (id, "fileId", "documentId", "assignmentId", "feedbackId", "blogId", "agreementAndUserId", created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3516 (class 0 OID 118248)
-- Dependencies: 249
-- Data for Name: mailsettings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.mailsettings (id, title, created_at, updated_at, removed) FROM stdin;
4	Согласование документа	2022-06-23 12:59:45.660629	\N	\N
5	Исполнение поручения	2022-06-23 13:00:28.901246	\N	\N
6	Перенос срока исполнения поручения	2022-06-23 13:00:55.786691	\N	\N
7	Ознакомление с документом	2022-09-15 14:55:37.394006	\N	\N
8	Назначение на личный контроль	2022-09-15 14:55:37.394006	\N	\N
9	Отказ в согласовании документа	2023-02-16 13:54:29.017878	\N	\N
10	Оповещение о ходе согласования	2023-03-22 16:29:53.215162	\N	\N
-- \.


--
-- TOC entry 3518 (class 0 OID 118254)
-- Dependencies: 251
-- Data for Name: mailsettings_users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.mailsettings_users (id, "userId", "settingId", created_at, updated_at, removed, status) FROM stdin;
-- \.


--
-- TOC entry 3520 (class 0 OID 118259)
-- Dependencies: 253
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
4	2014_10_12_000000_create_users_table	1
5	2014_10_12_100000_create_password_resets_table	1
6	2019_08_19_000000_create_failed_jobs_table	1
-- \.


--
-- TOC entry 3522 (class 0 OID 118264)
-- Dependencies: 255
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_resets (email, token, created_at) FROM stdin;
-- \.


--
-- TOC entry 3523 (class 0 OID 118270)
-- Dependencies: 256
-- Data for Name: report_agreements; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.report_agreements (id, "reportId", "userId", created_at, agreed_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3525 (class 0 OID 118276)
-- Dependencies: 258
-- Data for Name: reports; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.reports (id, "documentId", text, "authorId", "assignmentId", "appId", created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3527 (class 0 OID 118282)
-- Dependencies: 260
-- Data for Name: responsibles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.responsibles (id, "assignmentId", "userId", created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3529 (class 0 OID 118288)
-- Dependencies: 262
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (id, title, slug, created_at, updated_at, removed) FROM stdin;
1	Администратор	ADMIN	2021-06-03 14:29:20.298417	\N	\N
2	Пользователь	USER	2021-06-03 14:29:20.298417	\N	\N
-- \.


--
-- TOC entry 3530 (class 0 OID 118292)
-- Dependencies: 263
-- Data for Name: statuses; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.statuses (id, title, created_at, updated_at, removed, alias, "group") FROM stdin;
1	На согласовании	2022-03-30 10:43:16.292594	\N	\N	approving	1
2	Не согласован	2022-03-30 10:43:16.292594	\N	\N	refused	1
3	Согласован	2022-03-30 10:43:16.292594	\N	\N	approved	1
4	В архиве	2022-03-30 10:43:16.292594	\N	\N	in_archive	1
5	Удалён	2022-03-30 10:43:16.292594	\N	\N	deleted	1
6	На рассмотрении	2022-03-30 10:52:36.380997	\N	\N	considering	2
8	Отклонено	2022-03-30 10:52:36.380997	\N	\N	rejected	2
9	Исполнено	2022-03-30 10:52:36.380997	\N	\N	done	2
10	Отклонено автором	2022-03-30 10:52:36.380997	\N	\N	rejected_by_author	2
7	На исполнении	2022-03-30 10:52:36.380997	\N	\N	execution	2
11	Отменено автором	2022-04-07 16:22:11.965341	\N	\N	refused_by_author	1
-- \.


--
-- TOC entry 3531 (class 0 OID 118296)
-- Dependencies: 264
-- Data for Name: text_timezone; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.text_timezone (id, "time") FROM stdin;
2	2022-01-12 17:14:34.265395
-- \.


--
-- TOC entry 3533 (class 0 OID 118301)
-- Dependencies: 266
-- Data for Name: userpositions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.userpositions (id, title, created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3536 (class 0 OID 118309)
-- Dependencies: 269
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, login, surname, firstname, patronymic, department, email, email_verified_at, password, remember_token, created_at, updated_at, removed, roleid) FROM stdin;
-- \.


--
-- TOC entry 3537 (class 0 OID 118317)
-- Dependencies: 270
-- Data for Name: users_and_departments; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users_and_departments (id, "userId", "departmentId", created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3539 (class 0 OID 118322)
-- Dependencies: 272
-- Data for Name: users_and_userpositions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users_and_userpositions (id, "userId", "positionId", created_at, updated_at, removed) FROM stdin;
-- \.


--
-- TOC entry 3581 (class 0 OID 0)
-- Dependencies: 197
-- Name: Acquaintances_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Acquaintances_id_seq"', 1, true);


--
-- TOC entry 3582 (class 0 OID 0)
-- Dependencies: 202
-- Name: agreements_and_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.agreements_and_users_id_seq', 1, true);


--
-- TOC entry 3583 (class 0 OID 0)
-- Dependencies: 203
-- Name: agreements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.agreements_id_seq', 1, true);


--
-- TOC entry 3584 (class 0 OID 0)
-- Dependencies: 205
-- Name: apps_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.apps_id_seq', 1, false);


--
-- TOC entry 3585 (class 0 OID 0)
-- Dependencies: 209
-- Name: assignments_and_notestatuses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.assignments_and_notestatuses_id_seq', 1, true);


--
-- TOC entry 3586 (class 0 OID 0)
-- Dependencies: 211
-- Name: assignments_deadlines_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.assignments_deadlines_id_seq', 1, true);


--
-- TOC entry 3587 (class 0 OID 0)
-- Dependencies: 212
-- Name: assignments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.assignments_id_seq', 1, false);


--
-- TOC entry 3588 (class 0 OID 0)
-- Dependencies: 213
-- Name: assignments_id_seq1; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.assignments_id_seq1', 1, true);


--
-- TOC entry 3589 (class 0 OID 0)
-- Dependencies: 215
-- Name: blog_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.blog_id_seq', 1, true);


--
-- TOC entry 3590 (class 0 OID 0)
-- Dependencies: 217
-- Name: contract_attributes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.contract_attributes_id_seq', 1, false);


--
-- TOC entry 3591 (class 0 OID 0)
-- Dependencies: 219
-- Name: control_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.control_id_seq', 1, true);


--
-- TOC entry 3592 (class 0 OID 0)
-- Dependencies: 221
-- Name: counterparties_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.counterparties_id_seq', 1, false);


--
-- TOC entry 3593 (class 0 OID 0)
-- Dependencies: 224
-- Name: delivery_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.delivery_types_id_seq', 3, true);


--
-- TOC entry 3594 (class 0 OID 0)
-- Dependencies: 226
-- Name: departments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.departments_id_seq', 1, true);


--
-- TOC entry 3595 (class 0 OID 0)
-- Dependencies: 229
-- Name: dirusers_and_documents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dirusers_and_documents_id_seq', 1, true);


--
-- TOC entry 3596 (class 0 OID 0)
-- Dependencies: 230
-- Name: dirusers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dirusers_id_seq', 1, true);


--
-- TOC entry 3597 (class 0 OID 0)
-- Dependencies: 232
-- Name: document_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.document_types_id_seq', 15, true);


--
-- TOC entry 3598 (class 0 OID 0)
-- Dependencies: 235
-- Name: documents_and_docstatuses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.documents_and_docstatuses_id_seq', 1, true);


--
-- TOC entry 3599 (class 0 OID 0)
-- Dependencies: 237
-- Name: documents_and_files_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.documents_and_files_id_seq', 1, true);


--
-- TOC entry 3600 (class 0 OID 0)
-- Dependencies: 238
-- Name: documents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.documents_id_seq', 1, true);


--
-- TOC entry 3601 (class 0 OID 0)
-- Dependencies: 240
-- Name: executors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.executors_id_seq', 1, false);


--
-- TOC entry 3602 (class 0 OID 0)
-- Dependencies: 242
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 3603 (class 0 OID 0)
-- Dependencies: 244
-- Name: feedbacks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.feedbacks_id_seq', 1, false);


--
-- TOC entry 3604 (class 0 OID 0)
-- Dependencies: 247
-- Name: files_and_adds_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.files_and_adds_id_seq', 1, true);


--
-- TOC entry 3605 (class 0 OID 0)
-- Dependencies: 248
-- Name: files_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.files_id_seq', 1, true);


--
-- TOC entry 3606 (class 0 OID 0)
-- Dependencies: 250
-- Name: mailsettings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.mailsettings_id_seq', 9, true);


--
-- TOC entry 3607 (class 0 OID 0)
-- Dependencies: 252
-- Name: mailsettings_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.mailsettings_users_id_seq', 1, true);


--
-- TOC entry 3608 (class 0 OID 0)
-- Dependencies: 254
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 6, true);


--
-- TOC entry 3609 (class 0 OID 0)
-- Dependencies: 257
-- Name: report_agreements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.report_agreements_id_seq', 1, false);


--
-- TOC entry 3610 (class 0 OID 0)
-- Dependencies: 259
-- Name: reports_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.reports_id_seq', 1, false);


--
-- TOC entry 3611 (class 0 OID 0)
-- Dependencies: 261
-- Name: responsibles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.responsibles_id_seq', 1, false);


--
-- TOC entry 3612 (class 0 OID 0)
-- Dependencies: 265
-- Name: text_timezone_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.text_timezone_id_seq', 2, true);


--
-- TOC entry 3613 (class 0 OID 0)
-- Dependencies: 267
-- Name: userpositions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.userpositions_id_seq', 1, false);


--
-- TOC entry 3614 (class 0 OID 0)
-- Dependencies: 271
-- Name: users_and_departments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_and_departments_id_seq', 1, true);


--
-- TOC entry 3615 (class 0 OID 0)
-- Dependencies: 273
-- Name: users_and_userpositions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_and_userpositions_id_seq', 1, false);


--
-- TOC entry 3616 (class 0 OID 0)
-- Dependencies: 268
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, true);


--
-- TOC entry 3195 (class 2606 OID 118363)
-- Name: acquaintances Acquaintances_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.acquaintances
    ADD CONSTRAINT "Acquaintances_pkey" PRIMARY KEY (id);


--
-- TOC entry 3197 (class 2606 OID 118365)
-- Name: additions additions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.additions
    ADD CONSTRAINT additions_pkey PRIMARY KEY (id);


--
-- TOC entry 3201 (class 2606 OID 118367)
-- Name: agreements_and_users agreements_and_users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.agreements_and_users
    ADD CONSTRAINT agreements_and_users_pkey PRIMARY KEY (id);


--
-- TOC entry 3199 (class 2606 OID 118369)
-- Name: agreements agreements_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.agreements
    ADD CONSTRAINT agreements_pkey PRIMARY KEY (id);


--
-- TOC entry 3203 (class 2606 OID 118371)
-- Name: apps apps_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.apps
    ADD CONSTRAINT apps_pkey PRIMARY KEY (id);


--
-- TOC entry 3211 (class 2606 OID 118373)
-- Name: assignments_deadlines assignment_deadlines_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments_deadlines
    ADD CONSTRAINT assignment_deadlines_pkey PRIMARY KEY (id);


--
-- TOC entry 3209 (class 2606 OID 118375)
-- Name: assignments_and_assignmentstatuses assignments_and_assignmentstatuses_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments_and_assignmentstatuses
    ADD CONSTRAINT assignments_and_assignmentstatuses_pkey PRIMARY KEY (id);


--
-- TOC entry 3207 (class 2606 OID 118377)
-- Name: assignments assignments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments
    ADD CONSTRAINT assignments_pkey PRIMARY KEY (id);


--
-- TOC entry 3205 (class 2606 OID 118379)
-- Name: assignment_types assignmenttypes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignment_types
    ADD CONSTRAINT assignmenttypes_pkey PRIMARY KEY (id);


--
-- TOC entry 3213 (class 2606 OID 118381)
-- Name: blog blog_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blog
    ADD CONSTRAINT blog_pkey PRIMARY KEY (id);


--
-- TOC entry 3215 (class 2606 OID 118383)
-- Name: contract_attributes contract_attributes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contract_attributes
    ADD CONSTRAINT contract_attributes_pkey PRIMARY KEY (id);


--
-- TOC entry 3217 (class 2606 OID 118385)
-- Name: controls control_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.controls
    ADD CONSTRAINT control_pkey PRIMARY KEY (id);


--
-- TOC entry 3219 (class 2606 OID 118387)
-- Name: counterparties counterparties_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.counterparties
    ADD CONSTRAINT counterparties_pkey PRIMARY KEY (id);


--
-- TOC entry 3221 (class 2606 OID 118389)
-- Name: deadlines deadlines_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.deadlines
    ADD CONSTRAINT deadlines_pkey PRIMARY KEY (id);


--
-- TOC entry 3223 (class 2606 OID 118391)
-- Name: delivery_types delivery_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.delivery_types
    ADD CONSTRAINT delivery_types_pkey PRIMARY KEY (id);


--
-- TOC entry 3225 (class 2606 OID 118393)
-- Name: departments departments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_pkey PRIMARY KEY (id);


--
-- TOC entry 3229 (class 2606 OID 118395)
-- Name: dirusers_and_documents dirusers_and_documents_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dirusers_and_documents
    ADD CONSTRAINT dirusers_and_documents_pkey PRIMARY KEY (id);


--
-- TOC entry 3227 (class 2606 OID 118397)
-- Name: dirusers dirusers_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dirusers
    ADD CONSTRAINT dirusers_pkey PRIMARY KEY (id);


--
-- TOC entry 3231 (class 2606 OID 118399)
-- Name: document_types document_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.document_types
    ADD CONSTRAINT document_types_pkey PRIMARY KEY (id);


--
-- TOC entry 3235 (class 2606 OID 118401)
-- Name: documents_and_docstatuses documents_and_docstatuses_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.documents_and_docstatuses
    ADD CONSTRAINT documents_and_docstatuses_pkey PRIMARY KEY (id);


--
-- TOC entry 3237 (class 2606 OID 118403)
-- Name: documents_and_files documents_and_files_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.documents_and_files
    ADD CONSTRAINT documents_and_files_pkey PRIMARY KEY (id);


--
-- TOC entry 3233 (class 2606 OID 118405)
-- Name: documents documents_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.documents
    ADD CONSTRAINT documents_pkey PRIMARY KEY (id);


--
-- TOC entry 3239 (class 2606 OID 118407)
-- Name: executors executors_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.executors
    ADD CONSTRAINT executors_pkey PRIMARY KEY (id);


--
-- TOC entry 3241 (class 2606 OID 118409)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 3243 (class 2606 OID 118411)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 3245 (class 2606 OID 118413)
-- Name: feedbacks feedbacks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.feedbacks
    ADD CONSTRAINT feedbacks_pkey PRIMARY KEY (id);


--
-- TOC entry 3249 (class 2606 OID 118415)
-- Name: files_and_additions files_and_adds_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.files_and_additions
    ADD CONSTRAINT files_and_adds_pkey PRIMARY KEY (id);


--
-- TOC entry 3247 (class 2606 OID 118417)
-- Name: files files_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.files
    ADD CONSTRAINT files_pkey PRIMARY KEY (id);


--
-- TOC entry 3251 (class 2606 OID 118419)
-- Name: mailsettings mailsettings_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mailsettings
    ADD CONSTRAINT mailsettings_pkey PRIMARY KEY (id);


--
-- TOC entry 3253 (class 2606 OID 118421)
-- Name: mailsettings_users mailsettings_users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mailsettings_users
    ADD CONSTRAINT mailsettings_users_pkey PRIMARY KEY (id);


--
-- TOC entry 3255 (class 2606 OID 118423)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 3258 (class 2606 OID 118425)
-- Name: report_agreements report_agreements_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.report_agreements
    ADD CONSTRAINT report_agreements_pkey PRIMARY KEY (id);


--
-- TOC entry 3260 (class 2606 OID 118427)
-- Name: reports reports_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reports
    ADD CONSTRAINT reports_pkey PRIMARY KEY (id);


--
-- TOC entry 3262 (class 2606 OID 118429)
-- Name: responsibles responsibles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.responsibles
    ADD CONSTRAINT responsibles_pkey PRIMARY KEY (id);


--
-- TOC entry 3264 (class 2606 OID 118431)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- TOC entry 3266 (class 2606 OID 118433)
-- Name: statuses statuses_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.statuses
    ADD CONSTRAINT statuses_pkey PRIMARY KEY (id);


--
-- TOC entry 3268 (class 2606 OID 118435)
-- Name: text_timezone text_timezone_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.text_timezone
    ADD CONSTRAINT text_timezone_pkey PRIMARY KEY (id);


--
-- TOC entry 3270 (class 2606 OID 118437)
-- Name: userpositions userpositions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.userpositions
    ADD CONSTRAINT userpositions_pkey PRIMARY KEY (id);


--
-- TOC entry 3278 (class 2606 OID 118439)
-- Name: users_and_departments users_and_departments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_and_departments
    ADD CONSTRAINT users_and_departments_pkey PRIMARY KEY (id);


--
-- TOC entry 3280 (class 2606 OID 118441)
-- Name: users_and_userpositions users_and_userpositions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_and_userpositions
    ADD CONSTRAINT users_and_userpositions_pkey PRIMARY KEY (id);


--
-- TOC entry 3272 (class 2606 OID 118443)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 3274 (class 2606 OID 118445)
-- Name: users users_login_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_login_unique UNIQUE (login);


--
-- TOC entry 3276 (class 2606 OID 118447)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 3256 (class 1259 OID 118448)
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- TOC entry 3288 (class 2606 OID 118449)
-- Name: apps apps_assignmentId_assignments_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.apps
    ADD CONSTRAINT "apps_assignmentId_assignments_id" FOREIGN KEY ("assignmentId") REFERENCES public.assignments(id) NOT VALID;


--
-- TOC entry 3281 (class 2606 OID 118454)
-- Name: acquaintances fk_acquaintances_documentId_documents_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.acquaintances
    ADD CONSTRAINT "fk_acquaintances_documentId_documents_id" FOREIGN KEY ("documentId") REFERENCES public.documents(id) NOT VALID;


--
-- TOC entry 3282 (class 2606 OID 118459)
-- Name: acquaintances fk_acquaintances_initiatorId_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.acquaintances
    ADD CONSTRAINT "fk_acquaintances_initiatorId_users_id" FOREIGN KEY ("initiatorId") REFERENCES public.users(id) NOT VALID;


--
-- TOC entry 3283 (class 2606 OID 118464)
-- Name: acquaintances fk_acquaintances_userId_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.acquaintances
    ADD CONSTRAINT "fk_acquaintances_userId_users_id" FOREIGN KEY ("userId") REFERENCES public.users(id) NOT VALID;


--
-- TOC entry 3284 (class 2606 OID 118469)
-- Name: additions fk_additions_fileId_files_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.additions
    ADD CONSTRAINT "fk_additions_fileId_files_id" FOREIGN KEY ("fileId") REFERENCES public.files(id) NOT VALID;


--
-- TOC entry 3285 (class 2606 OID 118474)
-- Name: agreements fk_agreement_documentId_documents_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.agreements
    ADD CONSTRAINT "fk_agreement_documentId_documents_id" FOREIGN KEY ("documentId") REFERENCES public.documents(id);


--
-- TOC entry 3286 (class 2606 OID 118479)
-- Name: agreements_and_users fk_agreements_and_users_agreementId_agreeements_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.agreements_and_users
    ADD CONSTRAINT "fk_agreements_and_users_agreementId_agreeements_id" FOREIGN KEY ("agreementId") REFERENCES public.agreements(id);


--
-- TOC entry 3287 (class 2606 OID 118484)
-- Name: agreements_and_users fk_agreements_and_users_userId_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.agreements_and_users
    ADD CONSTRAINT "fk_agreements_and_users_userId_users_id" FOREIGN KEY ("userId") REFERENCES public.users(id);


--
-- TOC entry 3289 (class 2606 OID 118489)
-- Name: apps fk_apps_documentId_documents_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.apps
    ADD CONSTRAINT "fk_apps_documentId_documents_id" FOREIGN KEY ("documentId") REFERENCES public.documents(id);


--
-- TOC entry 3290 (class 2606 OID 118494)
-- Name: apps fk_apps_reportId_reports_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.apps
    ADD CONSTRAINT "fk_apps_reportId_reports_id" FOREIGN KEY ("reportId") REFERENCES public.reports(id);


--
-- TOC entry 3303 (class 2606 OID 118499)
-- Name: controls fk_assignmentId_controls_assignments_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.controls
    ADD CONSTRAINT "fk_assignmentId_controls_assignments_id" FOREIGN KEY ("assignmentId") REFERENCES public.assignments(id) NOT VALID;


--
-- TOC entry 3298 (class 2606 OID 118504)
-- Name: assignments_deadlines fk_assignment_deadlines_approvedUserId_users_Id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments_deadlines
    ADD CONSTRAINT "fk_assignment_deadlines_approvedUserId_users_Id" FOREIGN KEY ("approvedUserId") REFERENCES public.users(id);


--
-- TOC entry 3299 (class 2606 OID 118509)
-- Name: assignments_deadlines fk_assignment_deadlines_assignmentId_assignments_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments_deadlines
    ADD CONSTRAINT "fk_assignment_deadlines_assignmentId_assignments_id" FOREIGN KEY ("assignmentId") REFERENCES public.assignments(id) NOT VALID;


--
-- TOC entry 3300 (class 2606 OID 118514)
-- Name: assignments_deadlines fk_assignment_deadlines_fileId_files_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments_deadlines
    ADD CONSTRAINT "fk_assignment_deadlines_fileId_files_id" FOREIGN KEY ("fileId") REFERENCES public.files(id) NOT VALID;


--
-- TOC entry 3301 (class 2606 OID 118519)
-- Name: assignments_deadlines fk_assignment_deadlines_initiatorId_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments_deadlines
    ADD CONSTRAINT "fk_assignment_deadlines_initiatorId_users_id" FOREIGN KEY ("initiatorId") REFERENCES public.users(id);


--
-- TOC entry 3296 (class 2606 OID 118524)
-- Name: assignments_and_assignmentstatuses fk_assignments_and_assignmentstatuses_assignmentid_assignments_; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments_and_assignmentstatuses
    ADD CONSTRAINT fk_assignments_and_assignmentstatuses_assignmentid_assignments_ FOREIGN KEY ("assignmentId") REFERENCES public.assignments(id) NOT VALID;


--
-- TOC entry 3297 (class 2606 OID 118529)
-- Name: assignments_and_assignmentstatuses fk_assignments_and_assignmentstatuses_assignmentstatusid_status; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments_and_assignmentstatuses
    ADD CONSTRAINT fk_assignments_and_assignmentstatuses_assignmentstatusid_status FOREIGN KEY ("assignmentstatusId") REFERENCES public.statuses(id) NOT VALID;


--
-- TOC entry 3291 (class 2606 OID 118795)
-- Name: assignments fk_assignments_documentId_documents_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments
    ADD CONSTRAINT "fk_assignments_documentId_documents_id" FOREIGN KEY ("documentId") REFERENCES public.documents(id) NOT VALID;


--
-- TOC entry 3292 (class 2606 OID 118800)
-- Name: assignments fk_assignments_typeId_assignments_types_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments
    ADD CONSTRAINT "fk_assignments_typeId_assignments_types_id" FOREIGN KEY ("typeId") REFERENCES public.assignment_types(id) NOT VALID;


--
-- TOC entry 3293 (class 2606 OID 118805)
-- Name: assignments fk_authorId_assignments_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments
    ADD CONSTRAINT "fk_authorId_assignments_users_id" FOREIGN KEY ("authorId") REFERENCES public.users(id) NOT VALID;


--
-- TOC entry 3294 (class 2606 OID 118810)
-- Name: assignments fk_baseId_assignments_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments
    ADD CONSTRAINT "fk_baseId_assignments_users_id" FOREIGN KEY ("baseId") REFERENCES public.assignments(id) NOT VALID;


--
-- TOC entry 3302 (class 2606 OID 118554)
-- Name: contract_attributes fk_contrcontract_attributes_documentId_documents_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contract_attributes
    ADD CONSTRAINT "fk_contrcontract_attributes_documentId_documents_id" FOREIGN KEY ("documentId") REFERENCES public.documents(id);


--
-- TOC entry 3307 (class 2606 OID 118559)
-- Name: counterparties fk_counterparties_documentId_documents_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.counterparties
    ADD CONSTRAINT "fk_counterparties_documentId_documents_id" FOREIGN KEY ("documentId") REFERENCES public.departments(id);


--
-- TOC entry 3308 (class 2606 OID 118564)
-- Name: counterparties fk_counterparties_userId_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.counterparties
    ADD CONSTRAINT "fk_counterparties_userId_users_id" FOREIGN KEY ("userId") REFERENCES public.users(id);


--
-- TOC entry 3309 (class 2606 OID 118569)
-- Name: deadlines fk_deadline_initiatorId_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.deadlines
    ADD CONSTRAINT "fk_deadline_initiatorId_users_id" FOREIGN KEY ("initiatorId") REFERENCES public.users(id);


--
-- TOC entry 3310 (class 2606 OID 118574)
-- Name: deadlines fk_deadlines_approvedUserId_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.deadlines
    ADD CONSTRAINT "fk_deadlines_approvedUserId_users_id" FOREIGN KEY ("approvedUserId") REFERENCES public.users(id);


--
-- TOC entry 3311 (class 2606 OID 118579)
-- Name: deadlines fk_deadlines_documentId_documents_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.deadlines
    ADD CONSTRAINT "fk_deadlines_documentId_documents_id" FOREIGN KEY ("documentId") REFERENCES public.documents(id);


--
-- TOC entry 3312 (class 2606 OID 118584)
-- Name: departments fk_departments_headId_departments_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT "fk_departments_headId_departments_id" FOREIGN KEY ("headId") REFERENCES public.departments(id) NOT VALID;


--
-- TOC entry 3313 (class 2606 OID 118589)
-- Name: dirusers_and_documents fk_dirusers_and_documents_diruserId_dirusers_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dirusers_and_documents
    ADD CONSTRAINT "fk_dirusers_and_documents_diruserId_dirusers_id" FOREIGN KEY ("diruserId") REFERENCES public.dirusers(id) NOT VALID;


--
-- TOC entry 3314 (class 2606 OID 118594)
-- Name: dirusers_and_documents fk_dirusers_and_documents_documentId_documents_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dirusers_and_documents
    ADD CONSTRAINT "fk_dirusers_and_documents_documentId_documents_id" FOREIGN KEY ("documentId") REFERENCES public.documents(id) NOT VALID;


--
-- TOC entry 3304 (class 2606 OID 118599)
-- Name: controls fk_documentId_controls_documents_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.controls
    ADD CONSTRAINT "fk_documentId_controls_documents_id" FOREIGN KEY ("documentId") REFERENCES public.documents(id) NOT VALID;


--
-- TOC entry 3315 (class 2606 OID 118604)
-- Name: documents_and_docstatuses fk_documents_and_docstatuses_docstatusId_statuses_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.documents_and_docstatuses
    ADD CONSTRAINT "fk_documents_and_docstatuses_docstatusId_statuses_id" FOREIGN KEY ("docstatusId") REFERENCES public.statuses(id) NOT VALID;


--
-- TOC entry 3316 (class 2606 OID 118609)
-- Name: documents_and_docstatuses fk_documents_and_docstatuses_documentId_documents_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.documents_and_docstatuses
    ADD CONSTRAINT "fk_documents_and_docstatuses_documentId_documents_id" FOREIGN KEY ("documentId") REFERENCES public.documents(id);


--
-- TOC entry 3317 (class 2606 OID 118614)
-- Name: documents_and_files fk_documents_and_files_documentId_documents_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.documents_and_files
    ADD CONSTRAINT "fk_documents_and_files_documentId_documents_id" FOREIGN KEY ("documentId") REFERENCES public.documents(id) NOT VALID;


--
-- TOC entry 3318 (class 2606 OID 118619)
-- Name: documents_and_files fk_documents_and_files_fileId_files_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.documents_and_files
    ADD CONSTRAINT "fk_documents_and_files_fileId_files_id" FOREIGN KEY ("fileId") REFERENCES public.files(id) NOT VALID;


--
-- TOC entry 3295 (class 2606 OID 118815)
-- Name: assignments fk_executorId_assignments_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assignments
    ADD CONSTRAINT "fk_executorId_assignments_users_id" FOREIGN KEY ("executorId") REFERENCES public.users(id) NOT VALID;


--
-- TOC entry 3319 (class 2606 OID 118664)
-- Name: executors fk_executors_documentId_documents_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.executors
    ADD CONSTRAINT "fk_executors_documentId_documents_id" FOREIGN KEY ("documentId") REFERENCES public.documents(id);


--
-- TOC entry 3320 (class 2606 OID 118669)
-- Name: executors fk_executors_userId_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.executors
    ADD CONSTRAINT "fk_executors_userId_users_id" FOREIGN KEY ("userId") REFERENCES public.users(id);


--
-- TOC entry 3321 (class 2606 OID 118674)
-- Name: feedbacks fk_feedbacks_base_id_feedbacks_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.feedbacks
    ADD CONSTRAINT fk_feedbacks_base_id_feedbacks_id FOREIGN KEY ("baseId") REFERENCES public.feedbacks(id) NOT VALID;


--
-- TOC entry 3322 (class 2606 OID 118679)
-- Name: feedbacks fk_feedbacks_user_id_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.feedbacks
    ADD CONSTRAINT fk_feedbacks_user_id_users_id FOREIGN KEY ("userId") REFERENCES public.users(id);


--
-- TOC entry 3323 (class 2606 OID 118684)
-- Name: files_and_additions fk_files_and_additions_agreementAndUserId_agreements_and_users_; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.files_and_additions
    ADD CONSTRAINT "fk_files_and_additions_agreementAndUserId_agreements_and_users_" FOREIGN KEY ("agreementAndUserId") REFERENCES public.agreements_and_users(id) NOT VALID;


--
-- TOC entry 3324 (class 2606 OID 118689)
-- Name: files_and_additions fk_files_and_additions_assignmentId_assignments_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.files_and_additions
    ADD CONSTRAINT "fk_files_and_additions_assignmentId_assignments_id" FOREIGN KEY ("assignmentId") REFERENCES public.assignments(id) NOT VALID;


--
-- TOC entry 3325 (class 2606 OID 118694)
-- Name: files_and_additions fk_files_and_additions_blogId_blog_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.files_and_additions
    ADD CONSTRAINT "fk_files_and_additions_blogId_blog_id" FOREIGN KEY ("blogId") REFERENCES public.blog(id) NOT VALID;


--
-- TOC entry 3326 (class 2606 OID 118699)
-- Name: files_and_additions fk_files_and_additions_documentId_documents_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.files_and_additions
    ADD CONSTRAINT "fk_files_and_additions_documentId_documents_id" FOREIGN KEY ("documentId") REFERENCES public.documents(id) NOT VALID;


--
-- TOC entry 3327 (class 2606 OID 118704)
-- Name: files_and_additions fk_files_and_additions_feedbackId_feedbacks_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.files_and_additions
    ADD CONSTRAINT "fk_files_and_additions_feedbackId_feedbacks_id" FOREIGN KEY ("feedbackId") REFERENCES public.feedbacks(id) NOT VALID;


--
-- TOC entry 3305 (class 2606 OID 118709)
-- Name: controls fk_initiatorId_controls_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.controls
    ADD CONSTRAINT "fk_initiatorId_controls_users_id" FOREIGN KEY ("initiatorId") REFERENCES public.users(id) NOT VALID;


--
-- TOC entry 3328 (class 2606 OID 118714)
-- Name: mailsettings_users fk_mailsettings_users_settingId_mailsettings_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mailsettings_users
    ADD CONSTRAINT "fk_mailsettings_users_settingId_mailsettings_id" FOREIGN KEY ("settingId") REFERENCES public.mailsettings(id) NOT VALID;


--
-- TOC entry 3329 (class 2606 OID 118719)
-- Name: mailsettings_users fk_mailsettings_users_userId_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mailsettings_users
    ADD CONSTRAINT "fk_mailsettings_users_userId_users_id" FOREIGN KEY ("userId") REFERENCES public.users(id) NOT VALID;


--
-- TOC entry 3330 (class 2606 OID 118724)
-- Name: report_agreements fk_report_agreements_reportId_reports_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.report_agreements
    ADD CONSTRAINT "fk_report_agreements_reportId_reports_id" FOREIGN KEY ("reportId") REFERENCES public.reports(id);


--
-- TOC entry 3331 (class 2606 OID 118729)
-- Name: report_agreements fk_report_agreements_userId_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.report_agreements
    ADD CONSTRAINT "fk_report_agreements_userId_users_id" FOREIGN KEY ("userId") REFERENCES public.users(id);


--
-- TOC entry 3332 (class 2606 OID 118734)
-- Name: reports fk_reports_appId_apps_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reports
    ADD CONSTRAINT "fk_reports_appId_apps_id" FOREIGN KEY ("appId") REFERENCES public.reports(id) NOT VALID;


--
-- TOC entry 3333 (class 2606 OID 118739)
-- Name: reports fk_reports_assignmentId_assignments_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reports
    ADD CONSTRAINT "fk_reports_assignmentId_assignments_id" FOREIGN KEY ("assignmentId") REFERENCES public.assignments(id) NOT VALID;


--
-- TOC entry 3334 (class 2606 OID 118744)
-- Name: reports fk_reports_authorId_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reports
    ADD CONSTRAINT "fk_reports_authorId_users_id" FOREIGN KEY ("authorId") REFERENCES public.users(id);


--
-- TOC entry 3335 (class 2606 OID 118749)
-- Name: reports fk_reports_documentId_documents_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reports
    ADD CONSTRAINT "fk_reports_documentId_documents_id" FOREIGN KEY ("documentId") REFERENCES public.documents(id);


--
-- TOC entry 3336 (class 2606 OID 118754)
-- Name: responsibles fk_responsibles_userId_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.responsibles
    ADD CONSTRAINT "fk_responsibles_userId_users_id" FOREIGN KEY ("userId") REFERENCES public.users(id);


--
-- TOC entry 3306 (class 2606 OID 118759)
-- Name: controls fk_userId_controls_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.controls
    ADD CONSTRAINT "fk_userId_controls_users_id" FOREIGN KEY ("userId") REFERENCES public.users(id) NOT VALID;


--
-- TOC entry 3338 (class 2606 OID 118764)
-- Name: users_and_departments fk_users_and_departments_departmentId_departments_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_and_departments
    ADD CONSTRAINT "fk_users_and_departments_departmentId_departments_id" FOREIGN KEY ("departmentId") REFERENCES public.departments(id) NOT VALID;


--
-- TOC entry 3339 (class 2606 OID 118769)
-- Name: users_and_departments fk_users_and_departments_userId_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_and_departments
    ADD CONSTRAINT "fk_users_and_departments_userId_users_id" FOREIGN KEY ("userId") REFERENCES public.users(id) NOT VALID;


--
-- TOC entry 3340 (class 2606 OID 118774)
-- Name: users_and_userpositions fk_users_and_userpositions_userpositions_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_and_userpositions
    ADD CONSTRAINT fk_users_and_userpositions_userpositions_id FOREIGN KEY ("positionId") REFERENCES public.userpositions(id);


--
-- TOC entry 3341 (class 2606 OID 118779)
-- Name: users_and_userpositions fk_users_and_userpositions_users_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_and_userpositions
    ADD CONSTRAINT fk_users_and_userpositions_users_id FOREIGN KEY ("userId") REFERENCES public.users(id);


--
-- TOC entry 3337 (class 2606 OID 118784)
-- Name: users fk_users_roleid_roles_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT fk_users_roleid_roles_id FOREIGN KEY (roleid) REFERENCES public.roles(id) NOT VALID;


--
-- TOC entry 3546 (class 0 OID 0)
-- Dependencies: 7
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE USAGE ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2024-12-11 14:27:35

--
-- PostgreSQL database dump complete
--

