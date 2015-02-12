<?hh namespace Inspector;

class Inspector
{

    /**
     * @var string
     */
    protected $dest;

    public function __construct(protected Utilities\DirUtility $dir = null)
    {
    }

    public function copySourceTree(string $sourceDir): string
    {
        $sourceDir = INSPECTOR_WD."/".$sourceDir;

        $this->dest = "/tmp/".substr(md5($sourceDir), 0, 15);

        $this->dir->copy($sourceDir, $this->dest);

        return "<info>Copying the source tree ($sourceDir) into {$this->dest}...</info>";
    }

    public function placeMarkers(): string
    {
        // @todo
        return "Message for the user. ".$this->dest;
    }
}
