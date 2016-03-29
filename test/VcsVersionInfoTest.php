<?php
namespace nochso\Omni\Test;

use nochso\Omni\Exec;
use nochso\Omni\Folder;
use nochso\Omni\OS;
use nochso\Omni\Path;
use nochso\Omni\VcsVersionInfo;

class VcsVersionInfoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string Path to be used by all tests in this class
     */
    private static $base;

    public static function setUpBeforeClass()
    {
        self::$base = Path::combine(__DIR__, 'temp', 'VcsVersionInfo');
        if (is_dir(self::$base)) {
            Folder::delete(self::$base);
        }
    }

    public static function tearDownAfterClass()
    {
        Folder::delete(self::$base);
    }

    public function testGetInfoDefault_Fallback()
    {
        $version = new VcsVersionInfo('Name', '0.1.0', '/path/does/not/exist');
        $this->assertSame('Name 0.1.0', $version->getInfo());
        $this->assertSame('Name', $version->getName());
        $this->assertSame('0.1.0', $version->getVersion());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetInfoDefault_WhenNoFallBack_MustThrow()
    {
        $version = new VcsVersionInfo('Name', null, '/path/does/not/exist');
    }

    /**
     * @covers nochso\Omni\VcsVersionInfo::extractTag
     * @covers nochso\Omni\VcsVersionInfo::readGit
     */
    public function testGit()
    {
        if (!OS::hasBinary('git')) {
            $this->markTestSkipped('git has to be available for this test.');
        }
        $repoDir = Path::combine(self::$base, 'git');
        Folder::ensure($repoDir);

        // Set up a new repo with a single committed file
        Exec::create('git')->run('init', $repoDir);
        // From now on prefix all 'git' commands with git-dir and work-tree path
        $git = Exec::create(
            'git',
            '--git-dir=' . Path::combine($repoDir, '.git'),
            '--work-tree=' . $repoDir
        );

        $fooPath = Path::combine($repoDir, 'foo.txt');
        touch($fooPath);
        $git->run('add', $fooPath);
        $git->run('commit', '--allow-empty', '-m init');
        $vcs = new VcsVersionInfo('name', null, $repoDir);
        $this->assertRegExp('/^[0-9a-f]+$/', $vcs->getVersion(), 'Version without a tag must be rev hash');

        file_put_contents($fooPath, 'throw dirt at tree');
        $vcs = new VcsVersionInfo('name', null, $repoDir);
        $this->assertRegExp('/^[0-9a-f]+-dirty$/', $vcs->getVersion(), 'Dirty version without a tag must end in -dirty');

        $git->run('tag', '1.0.0');
        $vcs = new VcsVersionInfo('name', null, $repoDir);
        $this->assertSame('1.0.0-dirty', $vcs->getVersion(), 'Dirty version with a tag must end in -dirty');

        $git->run('checkout', $fooPath);
        $vcs = new VcsVersionInfo('name', null, $repoDir);
        $this->assertSame('1.0.0', $vcs->getVersion(), 'Clean version at specific tag');

        $git->run('commit', '--allow-empty', '-m move-on');
        $vcs = new VcsVersionInfo('name', null, $repoDir);
        $this->assertRegExp('/^1\.0\.0-1-g[0-9a-f]+$/', $vcs->getVersion(), 'Commit after latest tag');

        file_put_contents($fooPath, 'throw more dirt at tree');
        $vcs = new VcsVersionInfo('name', null, $repoDir);
        $this->assertRegExp('/^1\.0\.0-1-g[0-9a-f]+-dirty$/', $vcs->getVersion(), 'Commit after latest tag');

        Folder::delete($repoDir);
    }

    /**
     * @covers nochso\Omni\VcsVersionInfo::extractTag
     * @covers nochso\Omni\VcsVersionInfo::readMercurial
     */
    public function testMercurial()
    {
        if (!OS::hasBinary('hg')) {
            $this->markTestSkipped('hg (Mercurial) has to be available for this test.');
        }
        $repoDir = Path::combine(self::$base, 'hg');
        Folder::ensure($repoDir);

        // Set up a new repo with a single committed file
        Exec::create('hg')->run('init', $repoDir);
        // From now on prefix all 'hg' commands with repo and cwd path
        $hg = Exec::create('hg', '--repository=' . $repoDir, '--cwd=' . $repoDir);
        $fooPath = Path::combine($repoDir, 'foo.txt');
        touch($fooPath);
        $hg->run('add', $fooPath);
        $hg->run('commit', '-m init');

        $vcs = new VcsVersionInfo('name', null, $repoDir);
        $this->assertRegExp('/^[0-9a-f]+$/', $vcs->getVersion(), 'Version without a tag must be rev hash');

        file_put_contents($fooPath, 'throw dirt at tree');
        $vcs = new VcsVersionInfo('name', null, $repoDir);
        $this->assertRegExp('/^[0-9a-f]+-dirty$/', $vcs->getVersion(), 'Dirty version without a tag must end in -dirty');

        $hg->run('tag', '1.0.0');
        $hg->run('update', '1.0.0');
        $vcs = new VcsVersionInfo('name', null, $repoDir);
        $this->assertSame('1.0.0-dirty', $vcs->getVersion(), 'Dirty version with a tag must end in -dirty');

        $hg->run('update', '--clean', '1.0.0');
        $vcs = new VcsVersionInfo('name', null, $repoDir);
        $this->assertSame('1.0.0', $vcs->getVersion(), 'Clean version at specific tag');

        file_put_contents($fooPath, 'move on to next commit');
        $hg->run('commit', '-m move-on');
        $vcs = new VcsVersionInfo('name', null, $repoDir);
        $this->assertRegExp('/^1\.0\.0-1-m[0-9a-f]+$/', $vcs->getVersion(), 'Commit after latest tag');

        file_put_contents($fooPath, 'throw more dirt at tree');
        $vcs = new VcsVersionInfo('name', null, $repoDir);
        $this->assertRegExp('/^1\.0\.0-1-m[0-9a-f]+-dirty$/', $vcs->getVersion(), 'Commit after latest tag');

        Folder::delete($repoDir);
    }
}
