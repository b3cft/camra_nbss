my $data = '';
open F, $ARGV[0] or die "Can't open source file: $!";
$data .= $_ while <F>;
close F;

$data =~ s!\/\*(.*?)\*\/!!g;  # remove comments
$data =~ s!\s+! !g;           # collapse space
$data =~ s!\} !}\n!g;         # add line breaks
$data =~ s!\n$!!;             # remove last break
$data =~ s! \{ ! {!g;         # trim inside brackets
$data =~ s!; \}!}!g;          # trim inside brackets

print $data;