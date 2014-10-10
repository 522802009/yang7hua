var fs = require('fs');

function move(file1, file2)
{
	//fs.writeFileSync(file2, fs.readFileSync(file1));
	fs.createReadStream(file1).pipe(fs.createWriteStream(file2));
}

move(process.argv[2], process.argv[3]);

