<form method="post" action="addclass.php">
            <div class="line">
                <label for="cid">Course ID</label>
                <input type="text" name="cid" id="cid" placeholder="Department ####"/>
            </div>
            <div class="line">
                <label for="cname">Course Name</label>
                <input type="text" name="cname" id="cname" placeholder="Course Name"/>
            </div>
            <div class="line">
                <label for="instructor">Instructor</label>
                <input type="text" name="instructor" id="instructor" placeholder="First Name, Last Name"/>
            </div>
            <div class="line">
                <label for="csemester">Semester</label>
                <input type="text" name="csemester" id="csemester" placeholder="Semester (S/F, Year)"/>
            </div>
            <div class="line">
                <label for="terms"></label>
                <button type="submit">Add Class</button>
            </div>
</form>