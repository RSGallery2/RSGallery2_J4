# Phing tasks

2019.06.09

 [ ] 
 [ ] check 4 used language in admin and site ==> reduce lang files size
 [ ] ...
 
 >>> https://github.com/joomla/jissues/blob/master/build.xml
 [ ] ...separate phing for checkstyle.xml using PHP_CodeSniffer
 [ ] ...separate phing for   Perform syntax check of sourcecode files
		 <target name="lint" description="Perform syntax check of sourcecode files">
			<apply executable="php" failonerror="true">
				<arg value="-l" />

				<fileset dir="src">
					<include name="**/*.php" />
					<modified />
				</fileset>
				<fileset dir="cli">
					<include name="**/*.php" />
					<modified />
				</fileset>
				<fileset dir="www">
					<include name="index.php" />
					<modified />
				</fileset>
			</apply>
		</target>
 [ ] ...? separate phing for Generate API documentation using phpDocumentor
 [ ] ...separate phing for 
 
 [ ] Clean up and create artifact directories
		<target name="clean" description="Clean up and create artifact directories">
			<delete dir="${basedir}/build/coverage" />
			<delete dir="${basedir}/build/logs" />

			<mkdir dir="${basedir}/build/coverage" />
			<mkdir dir="${basedir}/build/logs" />
		</target>
<<< https://github.com/joomla/jissues/blob/master/build.xml

 [ ] ...
 [ ] ...
 [ ] ...
 [ ] ...
 [ ] ...
