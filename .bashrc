function cmdwin() {
        CMND=$1
        shift
        $CMND $* 2>&1 | iconv -f cp932 -t utf-8
}