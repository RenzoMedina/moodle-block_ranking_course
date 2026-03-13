# Ranking by course #

This section encourages healthy competition among students by showing who has the highest GPA in the current course. At the same time, it respects the privacy of those who do not wish to be publicly identified.

## Features ##

- Display the top 5 students with the highest GPA in the course.
- Privacy settings:
    -Visible names: displays full name and GPA.
    - Anonymous: Shows only rankings (1st, 2nd, 3rd…), with each student seeing their own ranking highlighted.
    - Teachers always see full names, regardless of the mode.
- Excludes users who do not have the student role or who have no recorded grades.

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/blocks/ranking_course

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## License ##

2026 Renzo Medina <medinast30@gmail.com>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
