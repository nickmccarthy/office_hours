<form method="post" action="addclass.php">
            <div class="line">
                <label for="cid">Course ID</label>
                <input type="text" name="cid" id="cid" placeholder="Department ####"/>
            <label class="error"></label>
            </div>
            <div class="line">
                <label for="cname">Course Name</label>
                <input type="text" name="cname" id="cname" placeholder="Course Name"/>
                        <label class="error"></label>
            </div>
            <div class="line">
                        <label for="fname">First Name</label>
                        <input type="text" name="fname" id="fname" placeholder="First Name"/>
                        <label class="error"></label>
            </div>
            <div class="line">
                        <label for="lname">Last Name</label>
                        <input type="text" name="lname" id="lname" placeholder="Last Name"/>
                        <label class="error"></label>
            </div>
            <div class="line">
                <label for="csemester">Semester</label>
                <input type="text" name="csemester" id="csemester" placeholder="Semester (SP/FA)"/>
                <label class="error"></label>
            </div>
            <div class="line">
                <label for="csemester">Year</label>
                <input type="text" name="cyear" id="cyear" placeholder="Year (####)"/>
                <label class="error"></label>
            </div>
            <div class="line">
                <label for="terms"></label>
                <button type="submit">Add Class</button>
            </div>
</form>